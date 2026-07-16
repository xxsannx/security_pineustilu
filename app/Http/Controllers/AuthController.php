<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OtpVerification;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Services\AuditLogService;

class AuthController extends Controller
{
    protected $fonnteService;

    public function __construct(FonnteService $fonnteService)
    {
        $this->fonnteService = $fonnteService;
    }

    public function showRegister()
    {
        return view('auth.register-otp');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/^\+?[1-9]\d{7,15}$/', 'unique:users'],
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
                'confirmed',
            ],
            'otp_method' => ['nullable', 'in:whatsapp,email'],
        ], [
            'phone.regex' => 'Silakan masukkan nomor HP yang valid dengan format internasional (contoh: +628123456789).',
            'phone.unique' => 'Nomor HP sudah terdaftar.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.letters' => 'Password must contain at least one letter.',
            'password.mixed' => 'Password must contain both uppercase and lowercase letters.',
            'password.numbers' => 'Password must contain at least one number.',
            'password.symbols' => 'Password must contain at least one symbol (e.g. !@#$%^&*).',
        ]);

        $otpMethod = $request->input('otp_method', 'whatsapp');

        // Create user with unverified phone
        $user = User::create([
            'name' => strip_tags($request->name),
            'email' => filter_var($request->email, FILTER_SANITIZE_EMAIL),
            'country_code' => '+62',
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'phone_verified_at' => null,
        ]);

        // Generate OTP
        $otp = (string) random_int(100000, 999999);
        
        OtpVerification::create([
            'phone_number' => $user->phone,
            'otp_hash' => Hash::make($otp),
            'expired_at' => now()->addMinutes(15),
            'attempts' => 0,
        ]);

        Log::info("OTP generated for registration: {$user->phone}");

        try {
            if ($otpMethod === 'email') {
                Mail::to($user->email)->queue(new OtpMail($otp));
                Log::info("OTP queued via Email to: {$user->email}");
                AuditLogService::log('otp_sent', "OTP registrasi dikirim via Email ke: {$user->email}", $user->id);
            } else {
                // Send OTP via Fonnte
                $this->fonnteService->sendOtp($user->phone, $otp);
                AuditLogService::log('otp_sent', "OTP registrasi dikirim via WhatsApp ke: {$user->phone}", $user->id);
            }
        } catch (\Exception $e) {
            Log::error("Failed to send OTP during registration: " . $e->getMessage() . " | Class: " . get_class($e));
            session()->flash('warning', 'Terdapat kendala saat mengirim OTP. Silakan gunakan tombol Kirim Ulang Kode di bawah atau ganti metode OTP.');
        }

        // Store phone, email, and method in persistent session (not flash)
        // so data survives AJAX requests, page refreshes, and multiple redirects
        session()->put('verify_phone', $user->phone);
        session()->put('verify_email', $user->email);
        session()->put('verify_otp_method', $otpMethod);

        return redirect()->route('otp.verify.form');
    }

    public function loginOtpStart(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'otp_method' => ['required', 'in:whatsapp,email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            AuditLogService::log('login_failed', "Login gagal: email/password salah untuk email '{$request->email}'", null);
            AuditLogService::checkBruteForce($request->ip());
            return back()->withInput($request->only('email', 'remember'))->withErrors([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ]);
        }

        // Bypass OTP untuk super-admin: langsung login tanpa perlu verifikasi OTP
        if ($user->hasRole('super-admin')) {
            Auth::login($user);
            Log::info("Super admin login bypass OTP: {$user->email}");
            AuditLogService::log(
                'login',
                "Super admin {$user->email} login langsung tanpa OTP",
                $user->id
            );
            return redirect()->intended('/dashboard')
                ->with('success', 'Selamat datang, ' . $user->name . '!');
        }

        if (empty($user->phone)) {
            return back()->withInput($request->only('email', 'remember'))->withErrors([
                'email' => 'Akun ini tidak memiliki nomor WhatsApp. Silakan register ulang atau hubungi admin.',
            ]);
        }

        // Generate OTP
        $otp = (string) random_int(100000, 999999);
        
        OtpVerification::create([
            'phone_number' => $user->phone,
            'otp_hash' => Hash::make($otp),
            'expired_at' => now()->addMinutes(15),
            'attempts' => 0,
        ]);

        Log::info("OTP generated for login: {$user->phone}");

        try {
            if ($request->otp_method === 'email') {
                Mail::to($user->email)->queue(new OtpMail($otp));
                Log::info("OTP queued via Email to: {$user->email}");
                AuditLogService::log('otp_sent', "OTP login dikirim via Email ke: {$user->email}", $user->id);
            } else {
                // Send OTP via Fonnte
                $this->fonnteService->sendOtp($user->phone, $otp);
                AuditLogService::log('otp_sent', "OTP login dikirim via WhatsApp ke: {$user->phone}", $user->id);
            }
        } catch (\Exception $e) {
            Log::error("Failed to send OTP during login: " . $e->getMessage() . " | Class: " . get_class($e));
            session()->flash('warning', 'Terdapat kendala saat mengirim OTP. Silakan gunakan tombol Kirim Ulang Kode di bawah atau ganti metode OTP.');
        }

        // Store phone, email, and method in persistent session (not flash)
        session()->put('verify_phone', $user->phone);
        session()->put('verify_email', $user->email);
        session()->put('verify_otp_method', $request->otp_method);

        return redirect()->route('otp.verify.form');
    }
}
