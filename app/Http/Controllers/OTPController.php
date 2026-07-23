<?php

namespace App\Http\Controllers;

use App\Models\OtpVerification;
use App\Models\User;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Services\AuditLogService;

class OTPController extends Controller
{
    protected $fonnteService;

    public function __construct(FonnteService $fonnteService)
    {
        $this->fonnteService = $fonnteService;
    }

    private function cleanExpiredOtps(): void
    {
        OtpVerification::where('expired_at', '<', now())->delete();
    }

    public function showVerifyForm()
    {
        $this->cleanExpiredOtps();

        if (!session()->has('verify_phone')) {
            return redirect()->route('register')->withErrors(['phone' => 'Sesi verifikasi telah berakhir. Silakan login atau register ulang.']);
        }
        
        // Calculate remaining cooldown from the latest OTP record for frontend sync
        $cooldownSeconds = 0;
        $phone = session('verify_phone');
        if ($phone) {
            $lastOtp = OtpVerification::where('phone_number', $phone)->latest()->first();
            if ($lastOtp) {
                $elapsed = (int) abs(now()->diffInSeconds($lastOtp->created_at));
                $cooldownSeconds = max(0, 60 - $elapsed);
            }
        }

        return view('auth.verify-otp', ['cooldownSeconds' => $cooldownSeconds]);
    }

    public function verify(Request $request)
    {
        $this->cleanExpiredOtps();

        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $phone = session('verify_phone');

        if (!$phone) {
            return redirect()->route('login')->withErrors(['email' => 'Sesi verifikasi telah kedaluwarsa. Silakan login kembali.']);
        }

        $otp = $request->otp;

        $otpRecord = OtpVerification::where('phone_number', $phone)
            ->latest()
            ->first();

        // Avoid revealing too much info; use generic error
        $genericError = 'Kode OTP tidak valid atau sudah kedaluwarsa.';

        if (!$otpRecord) {
            return back()->withInput()->withErrors(['otp' => $genericError]);
        }

        if (now()->greaterThan($otpRecord->expired_at)) {
            return back()->withInput()->withErrors(['otp' => 'Kode OTP sudah kedaluwarsa. Silakan request ulang.']);
        }

        if ($otpRecord->attempts >= 3) {
            return back()->withInput()->withErrors(['otp' => 'Terlalu banyak percobaan yang salah. Silakan request ulang kode OTP.']);
        }

        // Ambil metode OTP dari session untuk log yang akurat
        $otpMethod = session('verify_otp_method', 'whatsapp');
        $targetEmail = session('verify_email');
        $target = ($otpMethod === 'email' && $targetEmail) ? "Email: {$targetEmail}" : "WhatsApp: {$phone}";

        if (!Hash::check($otp, $otpRecord->otp_hash)) {
            $otpRecord->increment('attempts');

            // Log failed OTP attempt for security monitoring
            Log::warning('Failed OTP verification attempt', [
                'phone' => $phone,
                'ip' => $request->ip(),
                'attempts' => $otpRecord->attempts,
                'time' => now()->toDateTimeString(),
            ]);

            AuditLogService::log('otp_failed', "OTP salah untuk {$target}, percobaan ke-{$otpRecord->attempts}", null);

            return back()->withInput()->withErrors(['otp' => 'Kode OTP salah. Sisa percobaan: ' . (3 - $otpRecord->attempts)]);
        }

        // Verification success
        $user = User::where('phone', $phone)->first();
        if ($user) {
            $user->update(['phone_verified_at' => now()]);
            // Delete all OTP records for this phone number to prevent reuse
            OtpVerification::where('phone_number', $phone)->delete();
            
            Auth::login($user);
            Log::info("User successfully logged in via OTP: {$phone}");
            AuditLogService::log('otp_verified', "Login berhasil via OTP untuk {$target}", $user->id);
            
            // Clear session data
            session()->forget(['verify_phone', 'verify_email', 'verify_otp_method']);
            
            // Redirect berdasarkan role
            if ($user->hasRole('super-admin')) {
                return redirect('/admin')->with('success', 'Akun berhasil diverifikasi. Selamat datang Admin!');
            }
            
            return redirect()->route('dashboard')->with('success', 'Akun berhasil diverifikasi. Selamat datang!');
        }

        return redirect()->route('register')->withErrors(['phone' => 'Pengguna tidak ditemukan.']);
    }

    public function resend(Request $request)
    {
        $this->cleanExpiredOtps();

        $phone = $request->phone;
        $method = $request->input('method', 'whatsapp');
        
        if (!$phone) {
            return response()->json(['success' => false, 'message' => 'Nomor HP tidak valid.'], 400);
        }

        $lastOtp = OtpVerification::where('phone_number', $phone)->latest()->first();

        if ($lastOtp) {
            $elapsedSeconds = (int) abs(now()->diffInSeconds($lastOtp->created_at));
            if ($elapsedSeconds < 60) {
                $secondsLeft = 60 - $elapsedSeconds;
                return response()->json([
                    'success' => false, 
                    'message' => "Silakan tunggu {$secondsLeft} detik lagi untuk mengirim ulang OTP.",
                    'retry_after' => $secondsLeft,
                ], 429);
            }
        }

        $user = User::where('phone', $phone)->first();
        if (!$user) {
             return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan.'], 404);
        }

        // Invalidate all previous OTPs for this phone number before generating new one
        OtpVerification::where('phone_number', $phone)->delete();

        $otp = (string) random_int(100000, 999999);
        
        OtpVerification::create([
            'phone_number' => $phone,
            'otp_hash' => Hash::make($otp),
            'expired_at' => now()->addMinutes(15),
            'attempts' => 0,
        ]);

        if ($method === 'email') {
            if ($user->email) {
                try {
                    Mail::to($user->email)->send(new OtpMail($otp));
                    Log::info("Resend OTP via Email to {$user->email} successfully.");
                    AuditLogService::log('otp_sent', "OTP dikirim ulang via Email ke: {$user->email}", $user->id);
                    return response()->json(['success' => true, 'message' => 'OTP berhasil dikirim ulang ke Email.']);
                } catch (\Exception $e) {
                    Log::error("Resend OTP via Email failed: " . $e->getMessage());
                    return response()->json([
                        'success' => false, 
                        'message' => 'Gagal mengirim OTP via Email. Sedang ada gangguan pada server.',
                        'suggest_whatsapp' => true
                    ], 500);
                }
            }
            return response()->json(['success' => false, 'message' => 'Email pengguna tidak ditemukan.'], 404);
        }

        $sent = $this->fonnteService->sendOtp($phone, $otp);

        if ($sent) {
            Log::info("Resend OTP to {$phone} successfully.");
            AuditLogService::log('otp_sent', "OTP dikirim ulang via WhatsApp ke: {$phone}", $user->id);
            return response()->json(['success' => true, 'message' => 'OTP berhasil dikirim ulang via WhatsApp.']);
        }
        
        return response()->json([
            'success' => false, 
            'message' => 'Gagal mengirim OTP via WhatsApp. Pastikan Fonnte API terkonfigurasi dengan benar.',
            'suggest_email' => true
        ], 500);
    }
}
