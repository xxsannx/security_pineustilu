<?php

namespace App\Http\Controllers;

use App\Helpers\CurrencyHelper;
use App\Models\Outbound;
use App\Models\Price;

class AktivitasController extends Controller
{
    /**
     * Display activities page with database-backed outbound data.
     */
    public function index(): \Illuminate\View\View
    {
        $config = config('activities');
        $orderedSlugs = [
            'arung-jeram',
            'flying-fox',
            'offroad',
            'fun-atv',
            'paintball',
            'team-building',
        ];

        $outbounds = Outbound::query()
            ->with([
                'variants' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order'),
                'variants.prices' => fn ($query) => $query->whereNull('season_id'),
                'prices' => fn ($query) => $query->whereNull('outbound_variant_id')->whereNull('season_id'),
                'galleries' => fn ($query) => $query->where('type', 'outbound')->orderBy('id'),
            ])
            ->where('is_active', true)
            ->whereIn('slug', $orderedSlugs)
            ->orderBy('sort_order')
            ->get()
            ->keyBy('slug');

        $activities = collect($orderedSlugs)
            ->mapWithKeys(fn (string $slug) => [
                $slug => $this->buildActivityCard($slug, $outbounds->get($slug), $config['cards'][$slug] ?? []),
            ])
            ->all();

        $transportationPrice = Price::query()
            ->whereNull('outbound_id')
            ->whereNull('outbound_variant_id')
            ->whereNull('item_id')
            ->whereNull('unit_id')
            ->whereNull('season_id')
            ->value('price');

        $information = $this->buildInformation($config['information'] ?? [], $transportationPrice);

        return view('aktivitas', [
            'intro' => $config['intro'] ?? [],
            'activities' => $activities,
            'information' => $information,
            'aroundCards' => $config['around_cards'] ?? [],
        ]);
    }

    private function buildActivityCard(string $slug, ?Outbound $outbound, array $config): array
    {
        $variantPriceMap = $this->buildVariantPriceMap($outbound);
        $basePrice = $outbound?->prices->first()?->price;

        return [
            'slug' => $slug,
            'title' => (string) ($config['title'] ?? strtoupper((string) ($outbound?->name ?? ''))),
            'images' => $this->resolveImages($outbound, $config['fallback_images'] ?? []),
            'base_price' => $basePrice,
            'base_price_text' => CurrencyHelper::formatRupiah($basePrice),
            'variant_prices' => $variantPriceMap,
            'facilities_text' => (string) ($config['facilities_text'] ?? (string) ($outbound?->description ?? '')),
            'duration_text' => (string) ($config['duration_text'] ?? $this->formatDurationDistance($outbound)),
            'age_text' => (string) ($config['age_text'] ?? $this->defaultAgeText($outbound)),
            'min_participants' => $outbound?->min_participants,
            'max_participants' => $outbound?->max_participants,
            'variant_order' => $config['variant_order'] ?? [],
            'variant_notes' => $config['variant_notes'] ?? [],
            'variant_labels' => $config['variant_labels'] ?? [],
            'price_note_template' => $config['price_note_template'] ?? null,
            'details_url' => $config['details_url'] ?? null,
            'details_label' => $config['details_label'] ?? null,
            'camping_package_text' => $config['camping_package_text'] ?? null,
            'meal_package_text' => $config['meal_package_text'] ?? null,
            'meal_prices' => $config['meal_prices'] ?? [],
        ];
    }

    private function buildVariantPriceMap(?Outbound $outbound): array
    {
        if (!$outbound) {
            return [];
        }

        return $outbound->variants
            ->mapWithKeys(function ($variant) {
                return [
                    $variant->variant_label => CurrencyHelper::formatRupiah($variant->prices->first()?->price),
                ];
            })
            ->all();
    }

    private function resolveImages(?Outbound $outbound, array $fallbackImages): array
    {
        $images = collect($outbound?->galleries ?? [])
            ->map(function ($gallery) {
                return $gallery->image_url ?? ($gallery->image_path ? asset($gallery->image_path) : null);
            })
            ->filter()
            ->values();

        if ($images->isNotEmpty()) {
            return $images->all();
        }

        return collect($fallbackImages)
            ->map(fn (string $path) => asset($path))
            ->all();
    }

    private function buildInformation(array $information, float|int|null $transportationPrice): array
    {
        $priceText = CurrencyHelper::formatRupiah($transportationPrice ?? 200000);
        $pickupTemplate = (string) data_get($information, 'pickup.body_template', '');
        $pickupBody = str_replace(':price', $priceText, $pickupTemplate);

        $insuranceItems = collect(data_get($information, 'insurance.items', []))
            ->map(function (array $item) {
                return [
                    'label' => (string) ($item['label'] ?? ''),
                    'amount_text' => CurrencyHelper::formatRupiah($item['amount'] ?? null),
                ];
            })
            ->all();

        return [
            'pickup' => [
                'title' => (string) data_get($information, 'pickup.title', ''),
                'body' => $pickupBody,
                'price_text' => $priceText,
            ],
            'cancellation' => [
                'title' => (string) data_get($information, 'cancellation.title', ''),
                'body' => (string) data_get($information, 'cancellation.body', ''),
            ],
            'insurance' => [
                'title' => (string) data_get($information, 'insurance.title', ''),
                'intro' => (string) data_get($information, 'insurance.intro', ''),
                'items' => $insuranceItems,
            ],
        ];
    }

    private function formatDurationDistance(?Outbound $outbound): string
    {
        if (!$outbound) {
            return '-';
        }

        $distance = $outbound->distance_text;
        $duration = $outbound->duration_text;

        if ($distance && $duration) {
            return $distance . ' (' . $duration . ')';
        }

        return $duration ?? $distance ?? '-';
    }

    private function defaultAgeText(?Outbound $outbound): string
    {
        if (!$outbound || !$outbound->min_age) {
            return '-';
        }

        return 'Min. ' . $outbound->min_age . ' years old';
    }
}
