<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use Filament\Widgets\ChartWidget;

class ItemByTypeChart extends ChartWidget
{
    protected static ?string $heading = 'Additional Items by Type';

    protected static ?int $sort = 6;

    protected function getData(): array
    {
        $types = [
            'perlengkapan' => 'Perlengkapan',
            'makanan' => 'Makanan & Minuman',
            'sewa' => 'Sewa Alat',
            'layanan' => 'Additional Services',
            'lainnya' => 'Lainnya',
        ];

        $data = [];
        $labels = [];
        $colors = [
            'rgba(1, 114, 73, 0.8)',
            'rgba(234, 179, 8, 0.8)',
            'rgba(249, 115, 22, 0.8)',
            'rgba(59, 130, 246, 0.8)',
            'rgba(156, 163, 175, 0.8)',
        ];

        $i = 0;
        foreach ($types as $type => $label) {
            $count = Item::where('type', $type)->count();
            $data[] = $count;
            $labels[] = $label;
            $i++;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Item',
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}
