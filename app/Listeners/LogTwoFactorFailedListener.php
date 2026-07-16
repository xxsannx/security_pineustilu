<?php

namespace App\Listeners;

use App\Services\AuditLogService;
use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Listener untuk menangkap event autentikasi gagal dari Laravel.
 * Mendeteksi kegagalan verifikasi 2FA challenge dari Fortify.
 */
class LogTwoFactorFailedListener
{
    /**
     * Handle the event.
     * Dipanggil ketika Laravel memfire event \Illuminate\Auth\Events\Failed.
     * Kita filter khusus untuk yang terjadi pada tahap 2FA challenge
     * (session memiliki key 'login.id' yang diset oleh Fortify saat 2FA challenge aktif).
     */
    public function handle(Failed $event): void
    {
        try {
            // Fortify menyimpan 'login.id' ke session ketika user berada di tahap 2FA challenge
            $loginId = session()->get('login.id');

            if ($loginId) {
                $user = $event->user;
                $email = $user?->email ?? "ID: {$loginId}";

                AuditLogService::log(
                    '2fa_failed',
                    "Verifikasi 2FA gagal untuk user: {$email}",
                    $user?->id
                );
            } else {
                // Catat kegagalan login biasa
                $email = $event->credentials['email'] ?? 'unknown';
                AuditLogService::log(
                    'login_failed',
                    "Percobaan login gagal untuk email: {$email}",
                    null,
                    'WARNING'
                );

                // Cek brute force
                $ip = request()?->ip() ?? '0.0.0.0';
                AuditLogService::checkBruteForce($ip);
            }
        } catch (\Throwable $e) {
            // Jangan crash jika listener gagal
            \Illuminate\Support\Facades\Log::error('LogTwoFactorFailedListener failed', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
