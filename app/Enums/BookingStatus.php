<?php

namespace App\Enums;

/**
 * Enum representing the possible statuses of a booking.
 */
enum BookingStatus: string
{
    case PROSES = 'proses';
    case PEMBAYARAN = 'pembayaran';
    case BERHASIL = 'berhasil';
    case BERJALAN = 'berjalan';
    case SELESAI = 'selesai';
    case DIBATALKAN = 'dibatalkan';

    /**
     * Get the display label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::PROSES => 'Processing',
            self::PEMBAYARAN => 'Awaiting Payment',
            self::BERHASIL => 'Confirmed',
            self::BERJALAN => 'In Progress',
            self::SELESAI => 'Completed',
            self::DIBATALKAN => 'Cancelled',
        };
    }

    /**
     * Get the color for UI display (Filament/Tailwind).
     */
    public function color(): string
    {
        return match ($this) {
            self::PROSES => 'gray',
            self::PEMBAYARAN => 'warning',
            self::BERHASIL => 'success',
            self::BERJALAN => 'info',
            self::SELESAI => 'primary',
            self::DIBATALKAN => 'danger',
        };
    }

    /**
     * Get the icon for UI display.
     */
    public function icon(): string
    {
        return match ($this) {
            self::PROSES => 'heroicon-o-clock',
            self::PEMBAYARAN => 'heroicon-o-credit-card',
            self::BERHASIL => 'heroicon-o-check-circle',
            self::BERJALAN => 'heroicon-o-play',
            self::SELESAI => 'heroicon-o-check-badge',
            self::DIBATALKAN => 'heroicon-o-x-circle',
        };
    }

    /**
     * Get all statuses as options array for forms.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $status) => [$status->value => $status->label()])
            ->all();
    }

    /**
     * Check if the booking can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this, [self::PROSES, self::PEMBAYARAN, self::BERHASIL]);
    }

    /**
     * Check if the booking can be rescheduled.
     */
    public function canBeRescheduled(): bool
    {
        return in_array($this, [self::BERHASIL]);
    }
}
