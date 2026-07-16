<?php

namespace App\Providers;

use App\Listeners\LogTwoFactorFailedListener;
use App\Services\AuditLogService;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

/**
 * Service Provider khusus untuk mendaftarkan listener event audit logging.
 * Diregistrasikan di bootstrap/app.php sebagai provider tambahan.
 */
class AuditEventServiceProvider extends ServiceProvider
{
    /**
     * Event listener mappings untuk audit logging.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Tangkap event autentikasi gagal untuk mendeteksi kegagalan 2FA
        Failed::class => [
            LogTwoFactorFailedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        // Log User Registration
        Event::listen(Registered::class, function (Registered $event) {
            AuditLogService::log(
                'user_registered',
                "Akun baru terdaftar dengan email: {$event->user->email}",
                $event->user->id,
                'INFO'
            );
        });

        // Log Password Reset
        Event::listen(PasswordReset::class, function (PasswordReset $event) {
            AuditLogService::log(
                'password_reset',
                "Password berhasil diubah/direset untuk user: {$event->user->email}",
                $event->user->id,
                'WARNING'
            );
        });
    }
}
