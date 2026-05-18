<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\AreaUnit;
use App\Models\BookingDetail;
use App\Models\Price;
use App\Models\SeasonDate;
use App\Services\AvailabilityService;
use App\Services\PricingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicesTest extends TestCase
{
    use RefreshDatabase;

    private PricingService $pricingService;
    private AvailabilityService $availabilityService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pricingService = new PricingService();
        $this->availabilityService = new AvailabilityService();
    }

    // ============ PricingService Tests ============

    public function test_resolve_season_type_returns_weekday(): void
    {
        // Monday should be weekday
        $monday = Carbon::parse('2025-01-20'); // A Monday
        $this->assertEquals('weekday', $this->pricingService->resolveSeasonType($monday));
    }

    public function test_resolve_season_type_returns_weekend_for_friday(): void
    {
        $friday = Carbon::parse('2025-01-24'); // A Friday
        $this->assertEquals('weekend', $this->pricingService->resolveSeasonType($friday));
    }

    public function test_resolve_season_type_returns_weekend_for_saturday(): void
    {
        $saturday = Carbon::parse('2025-01-25'); // A Saturday
        $this->assertEquals('weekend', $this->pricingService->resolveSeasonType($saturday));
    }

    public function test_resolve_season_type_returns_high_season_when_in_range(): void
    {
        // Create high season date range
        SeasonDate::factory()->create([
            'season_type' => 'high_season',
            'start_date' => '2025-12-20',
            'end_date' => '2025-12-31',
        ]);

        $christmasDay = Carbon::parse('2025-12-25');
        $this->assertEquals('high_season', $this->pricingService->resolveSeasonType($christmasDay));
    }

    public function test_get_unit_base_price_returns_correct_price(): void
    {
        $this->seed();

        $unit = AreaUnit::query()->first();
        $season = SeasonDate::query()->where('season_type', 'weekday')->first();

        if (!$unit || !$season) {
            $this->markTestSkipped('No unit or season available');
        }

        Price::updateOrCreate(
            ['unit_id' => $unit->id, 'season_id' => $season->id],
            ['price' => 1500000]
        );

        $price = $this->pricingService->getUnitBasePrice($unit->id, 'weekday');
        $this->assertEquals(1500000, $price);
    }

    public function test_get_unit_base_price_returns_zero_for_invalid_unit(): void
    {
        $this->seed();

        $price = $this->pricingService->getUnitBasePrice(99999, 'weekday');
        $this->assertEquals(0.0, $price);
    }

    public function test_calculate_breakfast_charge(): void
    {
        // 5 people, default 2, rate 100000 => 3 extra * 100000 = 300000
        $charge = $this->pricingService->calculateBreakfastCharge(5, 2, 100000);
        $this->assertEquals(300000, $charge);
    }

    public function test_calculate_breakfast_charge_returns_zero_when_under_default(): void
    {
        // 2 people, default 4, rate 100000 => 0 extra
        $charge = $this->pricingService->calculateBreakfastCharge(2, 4, 100000);
        $this->assertEquals(0, $charge);
    }

    public function test_get_unit_prices_by_season_type_returns_array(): void
    {
        $this->seed();

        $prices = $this->pricingService->getUnitPricesBySeasonType();
        $this->assertIsArray($prices);
    }

    public function test_get_high_season_ranges_returns_array(): void
    {
        $this->seed();

        $ranges = $this->pricingService->getHighSeasonRanges();
        $this->assertIsArray($ranges);
    }

    // ============ AvailabilityService Tests ============

    public function test_build_area_units_returns_grouped_by_slug(): void
    {
        $this->seed();

        $areaUnits = $this->availabilityService->buildAreaUnits();

        $this->assertIsArray($areaUnits);

        // Verify structure
        foreach ($areaUnits as $slug => $units) {
            $this->assertIsString($slug);
            $this->assertIsArray($units);

            foreach ($units as $unit) {
                $this->assertArrayHasKey('id', $unit);
                $this->assertArrayHasKey('name', $unit);
            }
        }
    }

    public function test_is_unit_available_returns_true_when_no_bookings(): void
    {
        $this->seed();

        $unit = AreaUnit::query()->first();
        if (!$unit) {
            $this->markTestSkipped('No unit available');
        }

        $checkin = Carbon::now()->addDays(30)->toDateString();
        $checkout = Carbon::now()->addDays(31)->toDateString();

        $this->assertTrue(
            $this->availabilityService->isUnitAvailable($unit->id, $checkin, $checkout)
        );
    }

    public function test_is_unit_available_returns_false_when_booked(): void
    {
        $this->seed();

        $unit = AreaUnit::query()->first();
        if (!$unit) {
            $this->markTestSkipped('No unit available');
        }

        $checkin = Carbon::now()->addDays(30)->toDateString();
        $checkout = Carbon::now()->addDays(31)->toDateString();

        // Create a booking that overlaps
        BookingDetail::factory()->create([
            'unit_id' => $unit->id,
            'check_in' => $checkin,
            'check_out' => $checkout,
        ]);

        $this->assertFalse(
            $this->availabilityService->isUnitAvailable($unit->id, $checkin, $checkout)
        );
    }

    public function test_compute_availability_for_date_marks_booked_units(): void
    {
        $this->seed();

        $areaUnits = $this->availabilityService->buildAreaUnits();
        if (empty($areaUnits)) {
            $this->markTestSkipped('No area units available');
        }

        $firstSlug = array_key_first($areaUnits);
        $firstUnit = $areaUnits[$firstSlug][0] ?? null;

        if (!$firstUnit) {
            $this->markTestSkipped('No unit in first area');
        }

        $checkin = Carbon::now()->addDays(60)->toDateString();

        // Create booking for this unit
        BookingDetail::factory()->create([
            'unit_id' => $firstUnit['id'],
            'check_in' => $checkin,
            'check_out' => Carbon::now()->addDays(61)->toDateString(),
        ]);

        $availability = $this->availabilityService->computeAvailabilityForDate($areaUnits, $checkin);

        $this->assertEquals('booked', $availability[$firstUnit['id']]);
    }

    public function test_get_unit_extra_charges_returns_charges_per_unit(): void
    {
        $this->seed();

        $charges = $this->availabilityService->getUnitExtraCharges();

        $this->assertIsArray($charges);

        foreach ($charges as $unitId => $charge) {
            $this->assertIsInt($unitId);
            $this->assertArrayHasKey('default_people', $charge);
            $this->assertArrayHasKey('breakfast', $charge);
            $this->assertArrayHasKey('full', $charge);
        }
    }
}
