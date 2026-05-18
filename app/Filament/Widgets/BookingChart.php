<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class BookingChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Booking per Bulan';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $months = collect();
        $bookingData = collect();
        $confirmedData = collect();
        $cancelledData = collect();

        // Get last 12 months data
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push($date->translatedFormat('M Y'));

            $totalBookings = Booking::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

            $confirmed = Booking::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->whereIn('status', ['confirmed', 'completed'])
                ->count();

            $cancelled = Booking::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->where('status', 'cancelled')
                ->count();

            $bookingData->push($totalBookings);
            $confirmedData->push($confirmed);
            $cancelledData->push($cancelled);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Booking',
                    'data' => $bookingData->toArray(),
                    'backgroundColor' => 'rgba(1, 114, 73, 0.2)',
                    'borderColor' => 'rgba(1, 114, 73, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Confirmed/Completed',
                    'data' => $confirmedData->toArray(),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'borderColor' => 'rgba(34, 197, 94, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Cancelled',
                    'data' => $cancelledData->toArray(),
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)',
                    'borderColor' => 'rgba(239, 68, 68, 1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $months->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
