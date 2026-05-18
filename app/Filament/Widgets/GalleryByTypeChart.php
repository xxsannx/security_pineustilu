<?php

namespace App\Filament\Widgets;

use App\Models\Gallery;
use Filament\Widgets\ChartWidget;

class GalleryByTypeChart extends ChartWidget
{
    protected static ?string $heading = 'Gallery by Type';

    protected static ?int $sort = 5;

    protected function getData(): array
    {
        $types = [
            'header' => 'Area - Header',
            'skema_deck' => 'Area - Skema Deck',
            'tent' => 'Area - Tent',
            'galeri' => 'Area - Galeri',
            'dashboard_header' => 'Dashboard - Header',
            'dashboard_map' => 'Dashboard - Peta',
            'dashboard_galeri' => 'Dashboard - Galeri',
        ];

        $data = [];
        $labels = [];
        $colors = [
            'rgba(1, 114, 73, 0.8)',
            'rgba(11, 90, 62, 0.8)',
            'rgba(34, 197, 94, 0.8)',
            'rgba(74, 222, 128, 0.8)',
            'rgba(59, 130, 246, 0.8)',
            'rgba(99, 102, 241, 0.8)',
            'rgba(139, 92, 246, 0.8)',
        ];

        $i = 0;
        foreach ($types as $type => $label) {
            $count = Gallery::where('type', $type)->count();
            if ($count > 0) {
                $data[] = $count;
                $labels[] = $label;
            }
            $i++;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Gambar',
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'right',
                ],
            ],
        ];
    }
}
