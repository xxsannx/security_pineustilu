<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Dashboard Admin';

    protected static ?int $navigationSort = -2;

    public function getColumns(): int | string | array
    {
        return 2;
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverview::class,
            \App\Filament\Widgets\BookingStatsWidget::class,
            \App\Filament\Widgets\BookingChart::class,
            \App\Filament\Widgets\GalleryByTypeChart::class,
            \App\Filament\Widgets\ItemByTypeChart::class,
            \App\Filament\Widgets\LatestBookingsWidget::class,
        ];
    }
}
