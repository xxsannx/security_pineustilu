<?php

namespace App\Services;

use App\Models\Price;
use App\Models\SeasonDate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * Service class for handling pricing logic.
 * Centralizes all season-based pricing calculations.
 */
class PricingService
{
    /**
     * Cache TTL in seconds (10 minutes).
     */
    private const CACHE_TTL = 600;

    /**
     * Resolve the season type for a given date.
     *
     * @param Carbon $date The date to check
     * @return string 'high_season', 'weekend', or 'weekday'
     */
    public function resolveSeasonType(Carbon $date): string
    {
        // High season overrides day-of-week pricing.
        $isHighSeason = SeasonDate::query()
            ->where('season_type', 'high_season')
            ->whereDate('start_date', '<=', $date->toDateString())
            ->whereDate('end_date', '>=', $date->toDateString())
            ->exists();

        if ($isHighSeason) {
            return 'high_season';
        }

        // Weekend: Friday (5) and Saturday (6)
        return in_array($date->dayOfWeekIso, [5, 6], true) ? 'weekend' : 'weekday';
    }

    /**
     * Get the base price for a unit based on season type.
     *
     * @param int $unitId The unit ID
     * @param string $seasonType The season type
     * @return float The base price
     */
    public function getUnitBasePrice(int $unitId, string $seasonType): float
    {
        $seasonId = SeasonDate::query()
            ->where('season_type', $seasonType)
            ->value('id');

        if (!$seasonId) {
            return 0.0;
        }

        $price = Price::query()
            ->where('unit_id', $unitId)
            ->where('season_id', $seasonId)
            ->value('price');

        return $price !== null ? (float) $price : 0.0;
    }

    /**
     * Get total base price for a unit across a date range (check-in to check-out).
     * The checkout date is exclusive, so the total reflects the number of nights.
     *
     * @return array{total: float, nights: int, breakdown: array<int, array{date: string, season: string, price: float}>}
     */
    public function getUnitBasePriceForRange(int $unitId, Carbon $checkin, Carbon $checkout): array
    {
        $cursor = $checkin->copy()->startOfDay();
        $end = $checkout->copy()->startOfDay();

        $total = 0.0;
        $nights = 0;
        $breakdown = [];

        while ($cursor->lt($end)) {
            $seasonType = $this->resolveSeasonType($cursor);
            $price = $this->getUnitBasePrice($unitId, $seasonType);

            $total += $price;
            $nights++;
            $breakdown[] = [
                'date' => $cursor->toDateString(),
                'season' => $seasonType,
                'price' => $price,
            ];

            $cursor->addDay();
        }

        return [
            'total' => $total,
            'nights' => $nights,
            'breakdown' => $breakdown,
        ];
    }

    /**
     * Get all unit prices grouped by season type.
     *
     * @return array<int, array<string, float>>
     */
    public function getUnitPricesBySeasonType(): array
    {
        return Cache::remember('unit_prices_by_season', self::CACHE_TTL, function () {
            $unitPrices = [];

            $unitPriceRows = Price::with('season')
                ->whereNotNull('unit_id')
                ->whereNotNull('season_id')
                ->get();

            foreach ($unitPriceRows as $row) {
                $unitId = $row->unit_id;
                $seasonType = $row->season?->season_type;
                if (!$unitId || !$seasonType) {
                    continue;
                }
                $unitPrices[$unitId] ??= [];
                $unitPrices[$unitId][$seasonType] = (float) $row->price;
            }

            return $unitPrices;
        });
    }

    /**
     * Get minimum prices for units by season.
     *
     * @param array<int> $unitIds Array of unit IDs
     * @return array{weekday: float|null, weekend: float|null, high_season: float|null}
     */
    public function getMinPricesBySeason(array $unitIds): array
    {
        $priceBySeason = [
            'weekday' => null,
            'weekend' => null,
            'high_season' => null,
        ];

        if (empty($unitIds)) {
            return $priceBySeason;
        }

        $seasonIds = SeasonDate::query()
            ->whereIn('season_type', ['weekday', 'weekend', 'high_season'])
            ->pluck('id', 'season_type')
            ->all();

        foreach (array_keys($priceBySeason) as $type) {
            $seasonId = $seasonIds[$type] ?? null;
            if (!$seasonId) {
                continue;
            }

            $minPrice = Price::query()
                ->whereIn('unit_id', $unitIds)
                ->where('season_id', (int) $seasonId)
                ->min('price');

            $priceBySeason[$type] = $minPrice !== null ? (float) $minPrice : null;
        }

        return $priceBySeason;
    }

    /**
     * Get high season date ranges.
     *
     * @return array<int, array{start: string|null, end: string|null}>
     */
    public function getHighSeasonRanges(): array
    {
        return Cache::remember('high_season_ranges', self::CACHE_TTL, function () {
            return SeasonDate::query()
                ->where('season_type', 'high_season')
                ->get(['start_date', 'end_date'])
                ->map(static fn (SeasonDate $s) => [
                    'start' => $s->start_date ? Carbon::parse($s->start_date)->toDateString() : null,
                    'end' => $s->end_date ? Carbon::parse($s->end_date)->toDateString() : null,
                ])
                ->values()
                ->all();
        });
    }

    /**
     * Calculate extra breakfast charge based on people count.
     *
     * @param int $peopleCount Number of guests
     * @param int $defaultPeople Default people included in base price
     * @param float $breakfastRate Per-person breakfast rate
     * @return float Extra breakfast charge
     */
    public function calculateBreakfastCharge(int $peopleCount, int $defaultPeople, float $breakfastRate): float
    {
        $extraPeople = max(0, $peopleCount - $defaultPeople);
        return $extraPeople * $breakfastRate;
    }
}
