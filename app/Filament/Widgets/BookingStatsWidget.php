<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BookingStatsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();

        return [
            Stat::make('Booking Pending', $pendingBookings)
                ->description('Awaiting confirmation')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Booking Confirmed', $confirmedBookings)
                ->description('Already confirmed')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Completed Bookings', $completedBookings)
                ->description('Completed')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('primary'),

            Stat::make('Cancelled Bookings', $cancelledBookings)
                ->description('Cancelled')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
