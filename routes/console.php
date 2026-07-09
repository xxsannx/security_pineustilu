<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Booking;
use App\Services\AuditLogService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Batalkan otomatis booking dengan status 'proses' atau 'pembayaran' yang sudah lewat 24 jam
Schedule::call(function () {
    $expiredBookings = Booking::whereIn('status', ['proses', 'pembayaran'])
        ->where('created_at', '<', now()->subHours(24))
        ->get();

    foreach ($expiredBookings as $booking) {
        $booking->update(['status' => 'dibatalkan']);

        AuditLogService::log(
            'booking_expired_cleanup',
            "Booking {$booking->token_code} dibatalkan otomatis oleh sistem karena melewati batas pembayaran 24 jam.",
            $booking->user_id,
            'INFO'
        );
    }
})->hourly();
