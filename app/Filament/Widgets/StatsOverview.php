<?php

namespace App\Filament\Widgets;

use App\Models\Area;
use App\Models\Booking;
use App\Models\Gallery;
use App\Models\Item;
use App\Models\Outbound;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Get booking stats
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();

        // Get this month bookings
        $thisMonthBookings = Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            Stat::make('Total Area', Area::count())
                ->description('Total camping areas')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5])
                ->url(route('filament.admin.resources.areas.index')),

            Stat::make('Total Outbound', Outbound::count())
                ->description('Outbound activity packages')
                ->descriptionIcon('heroicon-m-flag')
                ->color('warning')
                ->chart([3, 5, 2, 7, 4, 6, 5])
                ->url(route('filament.admin.resources.outbounds.index')),

            Stat::make('Total Bookings', $totalBookings)
                ->description($thisMonthBookings . ' bookings this month')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary')
                ->chart([2, 4, 6, 8, 5, 7, 9])
                ->url(route('filament.admin.resources.bookings.index')),

            Stat::make('Galeri', Gallery::count())
                ->description('Total photos & images')
                ->descriptionIcon('heroicon-m-photo')
                ->color('info')
                ->chart([5, 8, 3, 6, 9, 4, 7])
                ->url(route('filament.admin.resources.galleries.index')),

            Stat::make('Users', User::count())
                ->description('Total registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('gray')
                ->chart([4, 5, 6, 7, 8, 9, 10])
                ->url(route('filament.admin.resources.users.index')),

            Stat::make('Additional Items', Item::count())
                ->description('Total items available')
                ->descriptionIcon('heroicon-m-cube')
                ->color('danger')
                ->chart([3, 4, 2, 5, 3, 6, 4])
                ->url(route('filament.admin.resources.items.index')),
        ];
    }
}
