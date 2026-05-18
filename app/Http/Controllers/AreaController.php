<?php

namespace App\Http\Controllers;

use App\Helpers\CurrencyHelper;
use App\Models\Area;
use App\Models\AreaUnit;
use App\Models\Price;
use App\Models\SeasonDate;
use App\Models\Facility;
use App\Models\Gallery;
use App\Models\Item;
use Illuminate\Support\Facades\Cache;

/**
 * Controller for Area pages (Pineus Tilu 1, 2, 3 VIP, 4, Cabin, Cabin VVIP).
 * Handles both display pages and API endpoints for area data.
 */
class AreaController extends Controller
{
    /**
     * Cache TTL in seconds (10 minutes).
     */
    private const CACHE_TTL = 600;

    /**
     * Display area page based on slug.
     *
     * @param string|null $slug Area slug (e.g., 'pineus-tilu-1', 'pineus-tilu-cabin-vvip')
     * @return \Illuminate\View\View
     */
    public function show(?string $slug = null): \Illuminate\View\View
    {
        $area = Cache::remember("area_{$slug}", self::CACHE_TTL, function () use ($slug) {
            return Area::where('slug', $slug)->firstOrFail();
        });

        // Cabin VIP/VVIP has specialized layout
        if (in_array($slug, ['pineus-tilu-cabin', 'pineus-tilu-cabin-vvip'], true)) {
            return $this->showCabin($area, $slug);
        }

        return $this->showStandardArea($area);
    }

    /**
     * Display standard area page (PT1, PT2, PT3 VIP, PT4, Cabin).
     */
    private function showStandardArea(Area $area): \Illuminate\View\View
    {
        $cacheKey = "area_standard_{$area->id}";
        
        $data = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($area) {
            $units = AreaUnit::where('area_id', $area->id)->get();

            if ($units->isEmpty()) {
                return [
                    'prices' => [],
                    'defaultPeople' => 4,
                    'maxPeople' => 6,
                    'galleries' => collect(),
                ];
            }

            $seasons = SeasonDate::all()->keyBy('season_type');
            $prices = $this->buildPricesBySeasonAndTent($units, $seasons, $area);

            // Fetch galleries for this area
            $galleries = Gallery::where('area_id', $area->id)
                ->orderBy('id', 'asc')
                ->get();

            return [
                'prices' => $prices,
                'defaultPeople' => $units->first()->default_people ?? 4,
                'maxPeople' => $units->first()->max_people ?? 6,
                'galleries' => $galleries,
            ];
        });

        return view('area-pineus-tilu', [
            'area' => $area,
            'prices' => $data['prices'],
            'defaultPeople' => $data['defaultPeople'],
            'maxPeople' => $data['maxPeople'],
            'galleries' => $data['galleries'],
            'formatRupiah' => fn($n) => CurrencyHelper::formatIdr($n),
        ]);
    }

    /**
     * Build prices array grouped by season and tent type.
     * Optimized to avoid N+1 queries by pre-loading all prices.
     */
    private function buildPricesBySeasonAndTent($units, $seasons, Area $area): array
    {
        $prices = [];
        $seasonTypes = ['weekday', 'weekend', 'high_season', 'ramadan_weekday', 'ramadan_weekend'];

        $unitIds = $units->pluck('id')->toArray();
        $allPrices = Price::whereIn('unit_id', $unitIds)->get()->groupBy(['unit_id', 'season_id']);

        foreach ($seasonTypes as $seasonType) {
            $seasonId = $seasons[$seasonType]->id ?? null;
            if (!$seasonId) {
                continue;
            }

            $prices[$seasonType] = [];
            foreach ($units->groupBy('tent_type') as $tentType => $unitsByType) {
                $unitNames = $unitsByType->pluck('name')->toArray();
                $priceValue = $this->getFirstUnitPriceFromCache($unitsByType, $seasonId, $allPrices);
                $unitLabel = $this->buildUnitLabel($unitNames, $area->slug);
                $tentTypeLabel = $this->formatTentTypeLabel((string) $tentType);

                $prices[$seasonType][] = [
                    'label' => $unitLabel . ' (' . $tentTypeLabel . ')',
                    'unit_names' => $unitLabel,
                    'tent_type' => $tentTypeLabel,
                    'price' => $priceValue ?? 0,
                ];
            }
        }

        return $prices;
    }

    /**
     * Get price from pre-loaded prices collection (no additional queries).
     */
    private function getFirstUnitPriceFromCache($units, int $seasonId, $allPrices): ?float
    {
        foreach ($units as $unit) {
            $unitPrices = $allPrices[$unit->id][$seasonId] ?? null;
            if ($unitPrices && $unitPrices->isNotEmpty()) {
                return (float) $unitPrices->first()->price;
            }
        }

        return null;
    }

    /**
     * Build unit label for pricing display (e.g. "Deck 1, 2" or "All Deck 1 - 21").
     */
    private function buildUnitLabel(array $unitNames, ?string $areaSlug = null): string
    {
        [$numbers, $nameTypes] = $this->extractUnitNumbersAndTypes($unitNames);

        if (empty($numbers)) {
            return implode(', ', $unitNames);
        }

        sort($numbers);
        $numbers = array_values(array_unique($numbers));

        $min = $numbers[0];
        $max = $numbers[count($numbers) - 1];
        $isContinuous = count($numbers) === (($max - $min) + 1);
        $hasPlotUnits = in_array('plot', $nameTypes, true);

        if ($areaSlug === 'pineus-tilu-4' && $hasPlotUnits && $isContinuous) {
            return "All Deck {$min} - {$max}";
        }

        return 'Deck ' . implode(', ', $numbers);
    }

    /**
     * Extract numeric unit indexes and detected unit name types (deck/plot).
     */
    private function extractUnitNumbersAndTypes(array $unitNames): array
    {
        $numbers = [];
        $types = [];

        foreach ($unitNames as $unitName) {
            if (!is_string($unitName)) {
                continue;
            }

            if (preg_match('/\b(deck|plot)\s*(\d+)\b/i', $unitName, $matches)) {
                $types[] = strtolower((string) ($matches[1] ?? ''));
                $numbers[] = (int) ($matches[2] ?? 0);
            }
        }

        return [array_filter($numbers), array_values(array_unique(array_filter($types)))];
    }

    /**
     * Normalize tent type label for display.
     */
    private function formatTentTypeLabel(string $tentType): string
    {
        $tentType = trim($tentType);

        if ($tentType === '') {
            return 'Tent';
        }

        if (preg_match('/^(tent|cabin)\b/i', $tentType)) {
            return $tentType;
        }

        return 'Tent ' . $tentType;
    }


    /**
     * Display Cabin VIP/VVIP page with specialized layout.
     */
    private function showCabin(Area $area, string $slug): \Illuminate\View\View
    {
        $tier = str_contains($slug, 'vvip') ? 'VVIP' : 'VIP';
        $cacheKey = "cabin_{$tier}_{$area->id}";

        $data = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($area, $tier) {
            // Unit names: 'Cabin' for VIP, 'Cabin VVIP' for VVIP
            $unitName = $tier === 'VVIP' ? 'Cabin VVIP' : 'Cabin';
            $unit = AreaUnit::where('area_id', $area->id)
                ->where('name', $unitName)
                ->first();

            $prices = $this->getCabinPricing($unit);

            $facilities = Facility::where('area_id', $area->id)
                ->orderBy('description')
                ->orderBy('name')
                ->get();

            $galleries = Gallery::where('area_id', $area->id)
                ->orderBy('id', 'asc')
                ->get();

            return [
                'unit' => $unit,
                'prices' => $prices,
                'facilities' => $facilities,
                'galleries' => $galleries,
            ];
        });

        return view('area-pineus-tilu-cabin', [
            'area' => $area,
            'unit' => $data['unit'],
            'prices' => $data['prices'],
            'facilities' => $data['facilities'],
            'galleries' => $data['galleries'],
            'cabinTier' => $tier,
            'formatRupiah' => fn($n) => CurrencyHelper::formatRupiah($n),
        ]);
    }

    /**
     * Get pricing data for cabin grouped by season type.
     */
    private function getCabinPricing(?AreaUnit $unit): array
    {
        if (!$unit) {
            return [];
        }

        $prices = [];
        $seasons = SeasonDate::all();

        foreach ($seasons as $season) {
            $priceRecord = Price::where('unit_id', $unit->id)
                ->where('season_id', $season->id)
                ->first();

            if ($priceRecord) {
                $prices[$season->season_type] = [
                    'season_name' => $season->season_name ?? ucwords(str_replace('_', ' ', $season->season_type)),
                    'price' => (float) $priceRecord->price,
                    'date_range' => $this->formatSeasonDateRange($season),
                    'description' => $season->description ?? '',
                ];
            }
        }

        return $prices;
    }

    /**
     * Format season date range for display.
     */
    private function formatSeasonDateRange(SeasonDate $season): ?string
    {
        if (!$season->start_date || !$season->end_date) {
            return null;
        }

        $startDate = $season->start_date instanceof \Carbon\Carbon
            ? $season->start_date
            : \Carbon\Carbon::parse($season->start_date);
        $endDate = $season->end_date instanceof \Carbon\Carbon
            ? $season->end_date
            : \Carbon\Carbon::parse($season->end_date);

        return $startDate->format('d M') . ' - ' . $endDate->format('d M Y');
    }

    // =========================================================================
    // API ENDPOINTS (for admin panel and JavaScript)
    // =========================================================================

    /**
     * Get all areas data via API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllAreas(): \Illuminate\Http\JsonResponse
    {
        $areas = Area::with([
            'areaUnits.prices.season',
            'facilities',
            'galleries'
        ])->get();

        return response()->json($areas);
    }

    /**
     * Get single area data via API.
     *
     * @param string $slug Area slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAreaData(string $slug): \Illuminate\Http\JsonResponse
    {
        $area = Area::with([
            'areaUnits.prices.season',
            'facilities',
            'galleries'
        ])->where('slug', $slug)->firstOrFail();

        return response()->json($area);
    }

    /**
     * Get all items (amenities) via API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getItems(): \Illuminate\Http\JsonResponse
    {
        $items = Item::with('prices')->get();

        return response()->json($items);
    }
}
