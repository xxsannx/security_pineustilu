<?php

namespace App\Services;

use App\Models\Area;
use App\Models\AreaUnit;
use App\Models\BookingDetail;
use Illuminate\Support\Facades\Cache;

/**
 * Service class for handling unit availability logic.
 * Centralizes availability checking and area unit retrieval.
 */
class AvailabilityService
{
    /**
     * Cache TTL in seconds (5 minutes - shorter due to dynamic nature).
     */
    private const CACHE_TTL = 300;

    /**
     * Build area units grouped by area slug.
     *
     * @return array<string, array<int, array{id:int,name:string,default_people:int|null,max_people:int|null}>>
     */
    public function buildAreaUnits(): array
    {
        return Cache::remember('area_units_by_slug', self::CACHE_TTL, function () {
            $areas = Area::query()
                ->select(['id', 'slug'])
                ->with([
                    'areaUnits' => function ($q) {
                        $q->select(['id', 'area_id', 'name', 'default_people', 'max_people'])->orderBy('id');
                    },
                ])
                ->get();

            $areaUnits = [];
            foreach ($areas as $area) {
                $areaUnits[$area->slug] = $area->areaUnits
                    ->map(function (AreaUnit $u) {
                        return [
                            'id' => $u->id,
                            'name' => $u->name,
                            'default_people' => $u->default_people,
                            'max_people' => $u->max_people,
                        ];
                    })
                    ->values()
                    ->all();
            }

            return $areaUnits;
        });
    }

    /**
     * Get extra charges configuration for all units.
     *
     * @return array<int, array{default_people:int, breakfast:float, full:float}>
     */
    public function getUnitExtraCharges(): array
    {
        return Cache::remember('unit_extra_charges', self::CACHE_TTL, function () {
            $rows = AreaUnit::query()
                ->join('areas', 'areas.id', '=', 'area_units.area_id')
                ->get([
                    'area_units.id as unit_id',
                    'area_units.default_people',
                    'areas.extra_charge_breakfast',
                    'areas.extra_charge_full',
                ]);

            $out = [];
            foreach ($rows as $r) {
                $unitId = (int) $r->unit_id;
                $out[$unitId] = [
                    'default_people' => (int) ($r->default_people ?? 0),
                    'breakfast' => $r->extra_charge_breakfast !== null ? (float) $r->extra_charge_breakfast : 0.0,
                    'full' => $r->extra_charge_full !== null ? (float) $r->extra_charge_full : 0.0,
                ];
            }

            return $out;
        });
    }

    /**
     * Compute availability for all units on a specific date.
     *
     * @param array<string, array<int, array{id:int,name:string,default_people:int|null,max_people:int|null}>> $areaUnits
     * @param string $checkin The check-in date (Y-m-d)
     * @return array<int, string> Unit ID => 'available' | 'booked'
     */
    public function computeAvailabilityForDate(array $areaUnits, string $checkin): array
    {
        $bookedUnitIds = BookingDetail::whereDate('check_in', '<=', $checkin)
            ->whereDate('check_out', '>', $checkin)
            ->pluck('unit_id')
            ->map(fn ($v) => (int) $v)
            ->all();

        $bookedSet = array_flip($bookedUnitIds);

        $availability = [];
        foreach ($areaUnits as $units) {
            foreach ($units as $u) {
                $availability[$u['id']] = isset($bookedSet[$u['id']]) ? 'booked' : 'available';
            }
        }

        return $availability;
    }

    /**
     * Check if a unit is available for a date range.
     *
     * @param int $unitId The unit ID
     * @param string $checkin Check-in date (Y-m-d)
     * @param string $checkout Check-out date (Y-m-d)
     * @param int|null $excludeBookingDetailId Optional booking detail ID to exclude (useful for reschedule)
     * @return bool True if available
     */
    public function isUnitAvailable(int $unitId, string $checkin, string $checkout, ?int $excludeBookingDetailId = null): bool
    {
        $query = BookingDetail::where('unit_id', $unitId)
            ->whereDate('check_in', '<', $checkout)
            ->whereDate('check_out', '>', $checkin);

        if ($excludeBookingDetailId !== null) {
            $query->where('id', '!=', $excludeBookingDetailId);
        }

        return !$query->exists();
    }
}
