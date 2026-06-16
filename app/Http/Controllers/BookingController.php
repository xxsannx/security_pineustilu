<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Helpers\CurrencyHelper;
use App\Helpers\ItemGroupHelper;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Area;
use App\Models\AreaUnit;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Cancellation;
use App\Models\Facility;
use App\Models\Item;
use App\Models\Price;
use App\Models\SeasonDate;
use App\Models\User;
use App\Services\AvailabilityService;
use App\Services\PricingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{
    public function __construct(
        private readonly PricingService $pricingService,
        private readonly AvailabilityService $availabilityService,
    ) {
    }

    /**
     * Show availability table page.
     */
    public function showAvailabilityTable(): \Illuminate\Contracts\View\View
    {
        // Get all areas for tabs
        $areas = $this->getAreasForTabs();

        // Build area units for all areas
        $allAreaUnits = $this->availabilityService->buildAreaUnits();

        // Group units by area slug
        $areaUnits = [];
        foreach ($allAreaUnits as $areaSlug => $units) {
            $areaUnits[$areaSlug] = collect($units)->map(function ($unit) {
                return [
                    'id' => $unit['id'],
                    'name' => $unit['name'],
                    'capacity' => $unit['default_people'] ?? null,
                ];
            })->toArray();
        }

        // Get availability data for the next 30 days for all areas
        $availabilityData = $this->getAvailabilityForDateRange($allAreaUnits, 30);

        $defaultArea = 'pineus-tilu-1';

        return view('availability', [
            'areas' => $areas,
            'areaUnits' => $areaUnits,
            'availabilityData' => $availabilityData,
            'defaultArea' => $defaultArea,
        ]);
    }

    /**
     * Get fresh availability data via AJAX (real-time updates).
     */
    public function getAvailabilityData(Request $request): \Illuminate\Http\JsonResponse
    {
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month - 1); // JS uses 0-11

        // Build area units for all areas
        $allAreaUnits = $this->availabilityService->buildAreaUnits();

        // Calculate days in the requested month
        $monthInt = (int) $month + 1; // Convert from JS (0-11) to PHP (1-12)
        $yearInt = (int) $year;
        $daysInMonth = Carbon::create($yearInt, $monthInt, 1)->daysInMonth;

        // Get availability data for the specific month
        $startDate = Carbon::create($yearInt, $monthInt, 1)->startOfDay();
        $availabilityData = $this->getAvailabilityForMonth($allAreaUnits, $startDate, $daysInMonth);

        return response()->json([
            'success' => true,
            'data' => $availabilityData,
            'meta' => [
                'year' => $yearInt,
                'month' => (int) $month,
                'days' => $daysInMonth,
                'updated_at' => Carbon::now()->toIso8601String(),
            ],
        ]);
    }

    /**
     * Start or update a glamping reservation draft from selected check-in date.
     */
    public function startGlampingDraft(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'checkin' => ['required', 'date', 'after_or_equal:today'],
            'checkout' => ['nullable', 'date', 'after:checkin'],
            'original_token' => ['nullable', 'string'],
        ]);

        $checkin = Carbon::parse((string) $validated['checkin'])->startOfDay()->toDateString();
        $checkout = isset($validated['checkout'])
            ? Carbon::parse((string) $validated['checkout'])->startOfDay()->toDateString()
            : Carbon::parse($checkin)->addDay()->toDateString();

        $excludeDetailId = null;
        if (!empty($validated['original_token'])) {
            $origBooking = \App\Models\Booking::where('token_code', $validated['original_token'])->with('bookingDetails')->first();
            if ($origBooking && $origBooking->bookingDetails->isNotEmpty()) {
                $excludeDetailId = $origBooking->bookingDetails->first()->id;
            }
        }

        $areaUnits = $this->availabilityService->buildAreaUnits();
        $unitStatuses = $this->availabilityService->computeAvailabilityForDateRange($areaUnits, $checkin, $checkout, $excludeDetailId);

        $draftId = (string) Str::uuid();
        $draft = [
            'id' => $draftId,
            'checkin' => $checkin,
            'selected_unit_id' => null,
            'updated_at' => Carbon::now()->toIso8601String(),
        ];

        $this->storeDraft($draft);

        return response()->json([
            'success' => true,
            'data' => [
                'draft_id' => $draftId,
                'checkin' => $checkin,
                'unit_statuses' => $unitStatuses,
            ],
        ]);
    }

    /**
     * Select a unit for an existing reservation draft.
     */
    public function selectGlampingDraftUnit(Request $request, string $draftId): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'unit_id' => ['required', 'integer', 'exists:area_units,id'],
        ]);

        $draft = $this->getDraft();
        if (!$draft || (string) ($draft['id'] ?? '') !== $draftId) {
            return response()->json([
                'success' => false,
                'message' => 'Reservation draft not found or expired.',
            ], Response::HTTP_NOT_FOUND);
        }

        $unitId = (int) $validated['unit_id'];
        $checkin = (string) ($draft['checkin'] ?? '');
        if ($checkin === '') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid draft state.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!$this->availabilityService->isUnitAvailable($unitId, $checkin, Carbon::parse($checkin)->addDay()->toDateString())) {
            return response()->json([
                'success' => false,
                'message' => 'Selected unit is not available for check-in date.',
            ], Response::HTTP_CONFLICT);
        }

        $draft['selected_unit_id'] = $unitId;
        $draft['updated_at'] = Carbon::now()->toIso8601String();
        $this->storeDraft($draft);

        $bookedDates = $this->collectBookedDatesForUnit($unitId, Carbon::parse($checkin)->startOfDay(), 120);

        return response()->json([
            'success' => true,
            'data' => [
                'draft_id' => $draftId,
                'unit_id' => $unitId,
                'booked_dates' => $bookedDates,
            ],
        ]);
    }

    /**
     * Get checkout options (disabled/booked dates) for selected unit in draft.
     */
    public function getGlampingDraftCheckoutOptions(string $draftId): \Illuminate\Http\JsonResponse
    {
        $draft = $this->getDraft();
        if (!$draft || (string) ($draft['id'] ?? '') !== $draftId) {
            return response()->json([
                'success' => false,
                'message' => 'Reservation draft not found or expired.',
            ], Response::HTTP_NOT_FOUND);
        }

        $unitId = (int) ($draft['selected_unit_id'] ?? 0);
        $checkin = (string) ($draft['checkin'] ?? '');
        if ($unitId <= 0 || $checkin === '') {
            return response()->json([
                'success' => false,
                'message' => 'Select a unit first.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $bookedDates = $this->collectBookedDatesForUnit($unitId, Carbon::parse($checkin)->startOfDay(), 120);

        return response()->json([
            'success' => true,
            'data' => [
                'draft_id' => $draftId,
                'unit_id' => $unitId,
                'booked_dates' => $bookedDates,
            ],
        ]);
    }

    /**
     * @return array<int, string>
     */
    private function collectBookedDatesForUnit(int $unitId, Carbon $startDate, int $days): array
    {
        $booked = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i)->toDateString();
            if (!$this->availabilityService->isUnitAvailable($unitId, $date, Carbon::parse($date)->addDay()->toDateString())) {
                $booked[] = $date;
            }
        }

        return $booked;
    }

    private function getDraft(): ?array
    {
        $raw = session('glamping_reservation_draft');
        return is_array($raw) ? $raw : null;
    }

    private function storeDraft(array $draft): void
    {
        session(['glamping_reservation_draft' => $draft]);
    }

    /**
     * Get areas for tab navigation
     */
    private function getAreasForTabs(): array
    {
        return [
            [
                'id' => 1,
                'slug' => 'pineus-tilu-1',
                'display_name' => 'PINEUS TILU 1',
                'tag' => null,
            ],
            [
                'id' => 2,
                'slug' => 'pineus-tilu-2',
                'display_name' => 'PINEUS TILU 2',
                'tag' => null,
            ],
            [
                'id' => 3,
                'slug' => 'pineus-tilu-3',
                'display_name' => 'PINEUS TILU 3',
                'tag' => 'VIP',
            ],
            [
                'id' => 4,
                'slug' => 'pineus-tilu-4',
                'display_name' => 'PINEUS TILU 4',
                'tag' => null,
            ],
            [
                'id' => 5,
                'slug' => 'pineus-tilu-cabin',
                'display_name' => 'CABIN',
                'tag' => null,
            ],
        ];
    }

    /**
     * Get availability data for date range across all areas
     */
    private function getAvailabilityForDateRange(array $allAreaUnits, int $days): array
    {
        $availabilityData = [];
        $today = Carbon::now()->startOfDay();

        foreach ($allAreaUnits as $areaSlug => $units) {
            $availabilityData[$areaSlug] = [];

            for ($i = 0; $i < $days; $i++) {
                $date = $today->copy()->addDays($i);
                $dateKey = $date->toDateString();

                // Get availability for this date
                $dateAvailability = $this->availabilityService->computeAvailabilityForDate(
                    [$areaSlug => $units],
                    $dateKey
                );

                $availabilityData[$areaSlug][$dateKey] = [];

                // Map unit availability
                foreach ($units as $unit) {
                    $unitId = $unit['id'];
                    $unitStatus = $dateAvailability[$unitId] ?? 'available';
                    $isAvailable = ($unitStatus === 'available');

                    // Determine status
                    $status = 'available';
                    if (!$isAvailable) {
                        $status = 'booked';
                    } else {
                        // Check if it's a special price date (high season)
                        $seasonType = $this->pricingService->resolveSeasonType($date);
                        if (in_array($seasonType, ['high_season', 'ramadan_weekday', 'ramadan_weekend'])) {
                            $status = 'special';
                        }
                    }

                    $availabilityData[$areaSlug][$dateKey][$unitId] = $status;
                }
            }
        }

        return $availabilityData;
    }

    /**
     * Get availability data for specific month across all areas
     */
    private function getAvailabilityForMonth(array $allAreaUnits, Carbon $startDate, int $daysInMonth): array
    {
        $availabilityData = [];

        foreach ($allAreaUnits as $areaSlug => $units) {
            $availabilityData[$areaSlug] = [];

            for ($day = 0; $day < $daysInMonth; $day++) {
                $date = $startDate->copy()->addDays($day);
                $dateKey = $date->toDateString();

                // Get availability for this date
                $dateAvailability = $this->availabilityService->computeAvailabilityForDate(
                    [$areaSlug => $units],
                    $dateKey
                );

                $availabilityData[$areaSlug][$dateKey] = [];

                // Map unit availability
                foreach ($units as $unit) {
                    $unitId = $unit['id'];
                    $unitStatus = $dateAvailability[$unitId] ?? 'available';
                    $isAvailable = ($unitStatus === 'available');

                    // Determine status
                    $status = 'available';
                    if (!$isAvailable) {
                        $status = 'booked';
                    } else {
                        // Check if it's a special price date (high season)
                        $seasonType = $this->pricingService->resolveSeasonType($date);
                        if (in_array($seasonType, ['high_season', 'ramadan_weekday', 'ramadan_weekend'])) {
                            $status = 'special';
                        }
                    }

                    $availabilityData[$areaSlug][$dateKey][$unitId] = $status;
                }
            }
        }

        return $availabilityData;
    }

    /**
     * Show Glamping reservation page with availability data.
     */
    public function showGlampingReservation(Request $request): \Illuminate\Contracts\View\View
    {
        $checkinDate = $this->resolveCheckinDate($request);
        $checkin = $checkinDate->toDateString();

        $areaUnits = $this->availabilityService->buildAreaUnits();
        $availability = $this->availabilityService->computeAvailabilityForDate($areaUnits, $checkin);
        $items = $this->getAmenityItems();
        $unitPrices = $this->pricingService->getUnitPricesBySeasonType();
        $highSeasonRanges = $this->pricingService->getHighSeasonRanges();
        $unitExtraCharges = $this->availabilityService->getUnitExtraCharges();

        /** @var User|null $authUser */
        $authUser = Auth::user();
        $contactPrefill = [
            'is_authenticated' => (bool) $authUser,
            'is_google' => (bool) ($authUser?->google_id),
            'name' => $authUser?->name,
            'email' => $authUser?->email,
            'country_code' => $authUser?->country_code,
            'phone' => $authUser?->phone,
        ];

        // Pass structured data to view
        return view('reservasi.reservasi-glamping', [
            'availability' => $availability,
            'areaUnits' => $areaUnits,
            'items' => $items,
            'unitPrices' => $unitPrices,
            'highSeasonRanges' => $highSeasonRanges,
            'unitExtraCharges' => $unitExtraCharges,
            'checkinDefault' => $checkin,
            'contactPrefill' => $contactPrefill,
        ]);
    }

    /**
     * JSON endpoint for the "Informasi Detail Area" modal.
     */
    public function getGlampingAreaInfo(Request $request, string $slug): \Illuminate\Http\JsonResponse
    {
        $area = Area::query()->where('slug', $slug)->first();
        if (!$area) {
            return response()->json([
                'message' => 'Area not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $checkinDate = $this->parseCheckinDate($request);
        $seasonType = $checkinDate ? $this->pricingService->resolveSeasonType($checkinDate) : 'weekday';

        $units = AreaUnit::query()->where('area_id', $area->id)->get(['id', 'default_people', 'max_people']);
        $unitIds = $units->pluck('id')->map(fn($v) => (int) $v)->values()->all();

        $capacity = $this->buildCapacitySummary($units);
        $priceBySeason = $this->pricingService->getMinPricesBySeason($unitIds);
        $facilities = $this->getFacilitiesByType($area->id);

        return response()->json([
            'area' => [
                'id' => (int) $area->id,
                'name' => (string) $area->name,
                'slug' => (string) $area->slug,
            ],
            'season' => [
                'type' => (string) $seasonType,
                'checkin' => $checkinDate?->toDateString(),
            ],
            'facilities' => [
                'private' => $facilities['private'],
                'public' => $facilities['public'],
            ],
            'prices' => [
                'weekday' => [
                    'amount' => $priceBySeason['weekday'],
                    'display' => $this->formatIdr($priceBySeason['weekday']),
                ],
                'weekend' => [
                    'amount' => $priceBySeason['weekend'],
                    'display' => $this->formatIdr($priceBySeason['weekend']),
                ],
                'high_season' => [
                    'amount' => $priceBySeason['high_season'],
                    'display' => $this->formatIdr($priceBySeason['high_season']),
                ],
            ],
            'capacity' => $capacity,
            'extra_charge' => [
                'full' => [
                    'amount' => $area->extra_charge_full !== null ? (float) $area->extra_charge_full : 0.0,
                    'display' => $this->formatIdr($area->extra_charge_full !== null ? (float) $area->extra_charge_full : 0.0),
                ],
                'breakfast' => [
                    'amount' => $area->extra_charge_breakfast !== null ? (float) $area->extra_charge_breakfast : 0.0,
                    'display' => $this->formatIdr($area->extra_charge_breakfast !== null ? (float) $area->extra_charge_breakfast : 0.0),
                ],
            ],
        ]);
    }

    private function parseCheckinDate(Request $request): ?Carbon
    {
        if (!$request->filled('checkin')) {
            return null;
        }

        try {
            return Carbon::parse((string) $request->input('checkin'))->startOfDay();
        } catch (\Throwable $e) {
            return null;
        }
    }

    /** @param \Illuminate\Support\Collection<int,AreaUnit> $units */
    private function buildCapacitySummary($units): array
    {
        $defaultPeopleMin = $units->min('default_people');
        $defaultPeopleMax = $units->max('default_people');
        $maxPeopleMin = $units->min('max_people');
        $maxPeopleMax = $units->max('max_people');

        return [
            'default_min' => $defaultPeopleMin !== null ? (int) $defaultPeopleMin : null,
            'default_max' => $defaultPeopleMax !== null ? (int) $defaultPeopleMax : null,
            'max_min' => $maxPeopleMin !== null ? (int) $maxPeopleMin : null,
            'max_max' => $maxPeopleMax !== null ? (int) $maxPeopleMax : null,
        ];
    }

    /** @return array{private: array<int,array{name:string,icon:string|null}>, public: array<int,array{name:string,icon:string|null}>} */
    private function getFacilitiesByType(int $areaId): array
    {
        $rows = Facility::query()
            ->where('area_id', $areaId)
            ->orderBy('name')
            ->get(['name', 'type', 'icon'])
            ->map(function (Facility $f) {
                $rawType = strtolower(trim((string) $f->type));
                $normalizedType = $rawType === 'shared' ? 'public' : $rawType;

                return [
                    'name' => (string) $f->name,
                    'type' => $normalizedType,
                    'icon' => $f->icon ? '/' . ltrim((string) $f->icon, '/') : null,
                ];
            });

        $privateFacilities = $rows
            ->filter(fn($f) => $f['type'] === 'private')
            ->map(fn($f) => ['name' => $f['name'], 'icon' => $f['icon']])
            ->values()
            ->all();

        $publicFacilities = $rows
            ->filter(fn($f) => $f['type'] === 'public')
            ->map(fn($f) => ['name' => $f['name'], 'icon' => $f['icon']])
            ->values()
            ->all();

        return [
            'private' => $privateFacilities,
            'public' => $publicFacilities,
        ];
    }

    /**
     * Format amount as Indonesian Rupiah.
     */
    private function formatIdr(?float $amount): string
    {
        return CurrencyHelper::formatRupiah($amount);
    }

    /**
     * Store a booking with validated request data.
     */
    public function store(StoreBookingRequest $request)
    {
        /** @var User|null $authUser */
        $authUser = Auth::user();

        $data = $request->validated();

        $checkin = Carbon::parse($data['checkin'])->startOfDay();
        $checkout = Carbon::parse($data['checkout'])->startOfDay();
        $this->assertValidBookingDates($checkin, $checkout);

        $unit = AreaUnit::query()->with(['area:id,slug,extra_charge_breakfast,extra_charge_full'])->findOrFail((int) $data['unit_id']);

        $people = (int) $data['guestCount'];
        $this->assertUnitCapacity($unit, $people);
        $this->assertUnitAvailable($unit->id, $checkin, $checkout);

        // Determine guest identity rules
        $guestName = $authUser?->name ?? $data['name'];
        $guestEmail = $authUser?->email ?? $data['email'];
        $guestPhone = $data['phone'];

        $this->persistGoogleUserPhoneIfNeeded($authUser, $guestPhone);

        // Pricing (server-side source of truth)
        $pricing = $this->pricingService->getUnitBasePriceForRange($unit->id, $checkin, $checkout);
        $basePrice = (float) $pricing['total'];
        $seasonSummary = [];
        foreach ($pricing['breakdown'] as $row) {
            $seasonKey = (string) ($row['season'] ?? 'weekday');
            if (!isset($seasonSummary[$seasonKey])) {
                $seasonSummary[$seasonKey] = [
                    'nights' => 0,
                    'total' => 0.0,
                ];
            }
            $seasonSummary[$seasonKey]['nights']++;
            $seasonSummary[$seasonKey]['total'] += (float) ($row['price'] ?? 0);
        }

        // Extract amenity quantities from form data
        $amenityQuantities = [];
        foreach (($data['amenities'] ?? []) as $itemId => $qty) {
            $qtyNum = (int) $qty;
            if ($qtyNum > 0) {
                $amenityQuantities[(int) $itemId] = $qtyNum;
            }
        }

        $defaultPeople = (int) ($unit->default_people ?? 0);
        $extraPeople = max(0, $people - $defaultPeople);
        $isEligibleExtraModeArea = in_array((string) ($unit->area->slug ?? ''), [
            'pineus-tilu-1',
            'pineus-tilu-2',
            'pineus-tilu-3-vip',
            'pineus-tilu-4',
        ], true);

        $extraChargeMode = (string) ($data['extra_charge_mode'] ?? '');
        if (!$isEligibleExtraModeArea) {
            $extraChargeMode = '';
        } elseif ($extraPeople > 0 && !in_array($extraChargeMode, ['full', 'breakfast'], true)) {
            $extraChargeMode = 'breakfast';
        }

        if ($isEligibleExtraModeArea && $extraPeople > 0) {
            $managedAmenityIds = Item::query()
                ->whereIn('name', ['Amenities', 'Amenities VIP', 'Extra Breakfast'])
                ->pluck('id')
                ->map(fn($id) => (int) $id)
                ->all();
            foreach ($managedAmenityIds as $managedId) {
                unset($amenityQuantities[$managedId]);
            }
        }

        $amenities = Item::with('prices')->whereIn('id', array_keys($amenityQuantities))->get();
        $extraChargeAmenities = 0.0;
        $amenityBreakdown = [];
        foreach ($amenities as $item) {
            $latest = $item->prices->sortByDesc('created_at')->first();
            $unitPrice = $latest ? (float) $latest->price : 0.0;
            $qty = $amenityQuantities[$item->id] ?? 0;
            $lineTotal = $unitPrice * $qty;
            $extraChargeAmenities += $lineTotal;
            $amenityBreakdown[] = [
                'id' => $item->id,
                'name' => $item->name,
                'type' => $item->type,
                'unit_price' => $unitPrice,
                'qty' => $qty,
                'line_total' => $lineTotal,
            ];
        }

        // Extra guest charge mode is sourced from area seeder rates.
        $fullRate = $unit->area && $unit->area->extra_charge_full !== null
            ? (float) $unit->area->extra_charge_full
            : 0.0;
        $breakfastRate = $unit->area && $unit->area->extra_charge_breakfast !== null
            ? (float) $unit->area->extra_charge_breakfast
            : 0.0;

        $selectedExtraRate = 0.0;
        if ($isEligibleExtraModeArea && $extraPeople > 0) {
            if ($extraChargeMode === 'full') {
                $selectedExtraRate = $fullRate;
            } else {
                $extraChargeMode = 'breakfast';
                $selectedExtraRate = $breakfastRate;
            }
        }

        $extraChargeGuestMode = $extraPeople * $selectedExtraRate;
        $extraCharge = $extraChargeAmenities + $extraChargeGuestMode;

        $token = $this->generateUniqueTokenCode();

        DB::transaction(function () use ($authUser, $guestName, $guestPhone, $guestEmail, $token, $unit, $checkin, $checkout, $people, $extraCharge, $basePrice, $amenityBreakdown, $pricing, $seasonSummary, $defaultPeople, $extraPeople, $fullRate, $breakfastRate, $selectedExtraRate, $extraChargeGuestMode, $extraChargeMode) {
            $booking = Booking::create([
                'user_id' => $authUser?->id,
                'booking_type' => 'glamping',
                'booking_date' => Carbon::now()->toDateString(),
                'token_code' => $token,
                'status' => BookingStatus::PROSES,
                'guest_name' => (string) $guestName,
                'guest_phone' => (string) $guestPhone,
                'guest_email' => (string) $guestEmail,
            ]);

            BookingDetail::create([
                'booking_id' => $booking->id,
                'unit_id' => $unit->id,
                'check_in' => $checkin->toDateString(),
                'check_out' => $checkout->toDateString(),
                'number_of_people' => $people,
                'total_extra_charge' => $extraCharge,
                'total_price' => ($basePrice + $extraCharge),
                'note' => json_encode([
                    'nights' => $pricing['nights'] ?? 1,
                    'season_breakdown' => $seasonSummary,
                    'nightly_breakdown' => $pricing['breakdown'] ?? [],
                    'amenities' => array_keys(array_filter($amenityBreakdown, fn($item) => $item['qty'] > 0)),
                    'amenities_breakdown' => $amenityBreakdown,
                    'extra_breakfast' => [
                        'default_people' => $defaultPeople,
                        'extra_people' => $extraPeople,
                        'rate' => $selectedExtraRate,
                        'amount' => $extraChargeGuestMode,
                        'mode' => $extraChargeMode,
                    ],
                    'extra_charge_rates' => [
                        'full' => $fullRate,
                        'breakfast' => $breakfastRate,
                    ],
                    'extra_charge_mode' => $extraChargeMode,
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]);
        });

        return redirect()->route('reservasi.detail-pesanan', ['token' => $token]);
    }

    private function resolveCheckinDate(Request $request): Carbon
    {
        $checkinDate = $request->input('checkin')
            ? Carbon::parse($request->input('checkin'))->startOfDay()
            : Carbon::now()->startOfDay();

        $today = Carbon::now()->startOfDay();
        return $checkinDate->lt($today) ? $today : $checkinDate;
    }

    /**
     * @return array<int, array{id:int,name:string,description:mixed,type:mixed,price:float|null,price_display:string|null,group:string}>
     */
    private function getAmenityItems(): array
    {
        return Item::with('prices')->get()
            ->map(function (Item $item) {
                $latest = $item->prices->sortByDesc('created_at')->first();
                $rawPrice = null;
                if ($latest && is_numeric($latest->price)) {
                    $rawPrice = (float) $latest->price;
                }

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'type' => $item->type,
                    'price' => $rawPrice,
                    'price_display' => $this->formatPrice($rawPrice, $item->type),
                    'group' => $this->determineItemGroup($item),
                ];
            })
            ->values()
            ->all();
    }

    private function assertValidBookingDates(Carbon $checkin, Carbon $checkout): void
    {
        $today = Carbon::now()->startOfDay();
        if ($checkin->lt($today)) {
            throw ValidationException::withMessages([
                'checkin' => 'Check-in date cannot be in the past',
            ]);
        }

        if (!$checkout->gt($checkin)) {
            throw ValidationException::withMessages([
                'checkout' => 'Check-out date must be after check-in',
            ]);
        }
    }

    private function assertUnitCapacity(AreaUnit $unit, int $people): void
    {
        if ($people > (int) $unit->max_people) {
            throw ValidationException::withMessages([
                'guestCount' => 'Number of visitors exceeds unit capacity',
            ]);
        }
    }

    private function assertUnitAvailable(int $unitId, Carbon $checkin, Carbon $checkout, ?int $excludeBookingDetailId = null): void
    {
        if (!$this->availabilityService->isUnitAvailable($unitId, $checkin->toDateString(), $checkout->toDateString(), $excludeBookingDetailId)) {
            throw ValidationException::withMessages([
                'unit_id' => 'Unit not available for selected dates',
            ]);
        }
    }

    private function persistGoogleUserPhoneIfNeeded(?User $authUser, string $guestPhone): void
    {
        if (!$authUser || !$authUser->google_id || !empty($authUser->phone) || empty($guestPhone)) {
            return;
        }

        try {
            $authUser->fill(['phone' => $guestPhone]);
            $authUser->save();
        } catch (\Throwable $e) {
            // ignore
        }
    }

    /**
     * Determine item group using centralized helper.
     */
    private function determineItemGroup(Item $item): string
    {
        return ItemGroupHelper::determineGroup($item);
    }

    /**
     * Format price with unit suffix using centralized helper.
     */
    private function formatPrice(?float $amount, ?string $type = null): ?string
    {
        return CurrencyHelper::formatPriceWithUnit($amount, $type, false);
    }

    private function generateUniqueTokenCode(): string
    {
        do {
            $token = strtoupper(Str::random(10));
        } while (Booking::query()->where('token_code', $token)->exists());

        return $token;
    }

    /**
     * Show booking detail/confirmation page.
     *
     * @param Request $request
     * @param string $token
     * @return \Illuminate\Contracts\View\View
     */
    public function showDetailPesanan(Request $request, string $token): \Illuminate\Contracts\View\View
    {
        // Fetch booking with related data for both types
        $booking = Booking::with(['bookingDetails.unit.area', 'bookingOutbounds.outbound', 'bookingOutbounds.outboundVariant'])
            ->where('token_code', $token)
            ->firstOrFail();

        // Map booking status to view status string
        $statusMap = [
            'proses' => 'booking',
            'pembayaran' => 'pembayaran',
            'berhasil' => 'berhasil',
            'berjalan' => 'berjalan',
            'selesai' => 'selesai',
            'dibatalkan' => 'dibatalkan',
        ];
        $status = $statusMap[$booking->status->value] ?? 'booking';

        // Check booking type and handle accordingly
        if ($booking->booking_type === 'outbound') {
            return $this->showOutboundDetailPesanan($booking, $token, $status);
        }

        // Continue with glamping booking logic
        $detail = $booking->bookingDetails->first();

        // Map booking status to view status string
        $statusMap = [
            'proses' => 'booking',
            'pembayaran' => 'pembayaran',
            'berhasil' => 'berhasil',
            'berjalan' => 'berjalan',
            'selesai' => 'selesai',
            'dibatalkan' => 'dibatalkan',
        ];
        $status = $statusMap[$booking->status->value] ?? 'booking';

        // Parse note JSON for amenities breakdown
        $note = $detail && $detail->note ? json_decode($detail->note, true) : [];
        $amenitiesBreakdown = $note['amenities_breakdown'] ?? [];
        $extraBreakfast = $note['extra_breakfast'] ?? [];
        $extraChargeMode = (string) ($note['extra_charge_mode'] ?? ($extraBreakfast['mode'] ?? 'breakfast'));

        // Get amenity names for display
        $amenities = collect($amenitiesBreakdown)
            ->filter(fn($item) => ($item['qty'] ?? 0) > 0)
            ->map(fn($item) => $item['name'])
            ->values()
            ->all();

        // Build additional costs array
        $additionalCosts = [];

        // Add amenities to additional costs
        foreach ($amenitiesBreakdown as $item) {
            if (($item['qty'] ?? 0) > 0) {
                $additionalCosts[] = [
                    'name' => $item['name'] . ' (x' . $item['qty'] . ')',
                    'price' => $this->formatIdr($item['line_total'] ?? 0),
                ];
            }
        }

        // Add breakfast extra charge if any
        if (($extraBreakfast['amount'] ?? 0) > 0) {
            $extraLabel = $extraChargeMode === 'full' ? 'Extra Full Amenities' : 'Extra Breakfast';
            $additionalCosts[] = [
                'name' => $extraLabel . ' (' . ($extraBreakfast['extra_people'] ?? 0) . ' pax)',
                'price' => $this->formatIdr($extraBreakfast['amount']),
            ];
        }

        // Calculate base price (total price - extra charge)
        $totalPrice = $detail ? (float) $detail->total_price : 0;
        $totalExtraCharge = $detail ? (float) $detail->total_extra_charge : 0;
        $basePrice = $totalPrice - $totalExtraCharge;

        // Format dates
        $checkinDate = $detail?->check_in;
        $checkoutDate = $detail?->check_out;

        $checkin = $checkinDate
            ? $checkinDate->translatedFormat('l, d M Y')
            : '-';
        $checkout = $checkoutDate
            ? $checkoutDate->translatedFormat('l, d M Y')
            : '-';

        // Build reschedule comparison info if this booking was rescheduled
        $rescheduleInfo = null;
        if (!empty($note['reschedule_info']['is_reschedule'])) {
            $ri = $note['reschedule_info'];
            $origCheckinFormatted = isset($ri['original']['checkin'])
                ? Carbon::parse($ri['original']['checkin'])->translatedFormat('l, d M Y')
                : '-';
            $origCheckoutFormatted = isset($ri['original']['checkout'])
                ? Carbon::parse($ri['original']['checkout'])->translatedFormat('l, d M Y')
                : '-';
            $newCheckinFormatted = isset($ri['new']['checkin'])
                ? Carbon::parse($ri['new']['checkin'])->translatedFormat('l, d M Y')
                : '-';
            $newCheckoutFormatted = isset($ri['new']['checkout'])
                ? Carbon::parse($ri['new']['checkout'])->translatedFormat('l, d M Y')
                : '-';

            $rescheduleInfo = [
                'payment_status' => $ri['payment_status'] ?? 'no_payment_required',
                'price_delta' => (float) ($ri['price_delta'] ?? 0),
                'payable_amount' => (float) ($ri['payable_amount'] ?? 0),
                'payable_display' => $this->formatIdr(abs((float) ($ri['price_delta'] ?? 0))),
                'original' => [
                    'checkin' => $origCheckinFormatted,
                    'checkout' => $origCheckoutFormatted,
                    'area_name' => $ri['original']['area_name'] ?? '-',
                    'unit_name' => $ri['original']['unit_name'] ?? '-',
                    'base_price' => $this->formatIdr((float) ($ri['original']['base_price'] ?? 0)),
                    'extra_charge' => $this->formatIdr((float) ($ri['original']['extra_charge'] ?? 0)),
                    'total_price' => $this->formatIdr((float) ($ri['original']['total_price'] ?? 0)),
                ],
                'new' => [
                    'checkin' => $newCheckinFormatted,
                    'checkout' => $newCheckoutFormatted,
                    'area_name' => $ri['new']['area_name'] ?? '-',
                    'unit_name' => $ri['new']['unit_name'] ?? '-',
                    'base_price' => $this->formatIdr((float) ($ri['new']['base_price'] ?? 0)),
                    'extra_charge' => $this->formatIdr((float) ($ri['new']['extra_charge'] ?? 0)),
                    'total_price' => $this->formatIdr((float) ($ri['new']['total_price'] ?? 0)),
                ],
            ];
        }

        return view('reservasi.detail-pesanan', [
            'token' => $token,
            'status' => $status,
            'booking' => $booking,
            'bookingType' => 'glamping',
            'name' => $booking->guest_name,
            'phone' => $booking->guest_phone,
            'email' => $booking->guest_email,
            'guestCount' => $detail?->number_of_people ?? 0,
            'checkin' => $checkin,
            'checkout' => $checkout,
            'area' => $detail?->unit?->area?->name ?? '-',
            'deck' => $detail?->unit?->name ?? '-',
            'basePrice' => $this->formatIdr($basePrice),
            'totalPrice' => $this->formatIdr($totalPrice),
            'totalExtraCharge' => $this->formatIdr($totalExtraCharge),
            'amenities' => $amenities,
            'additionalCosts' => $additionalCosts,
            'rescheduleInfo' => $rescheduleInfo,
        ]);
    }

    /**
     * Show outbound booking detail/confirmation page.
     *
     * @param Booking $booking
     * @param string $token
     * @param string $status
     * @return \Illuminate\Contracts\View\View
     */
    private function showOutboundDetailPesanan(Booking $booking, string $token, string $status): \Illuminate\Contracts\View\View
    {
        $outboundDetail = $booking->bookingOutbounds->first();

        // Parse note JSON
        $note = $outboundDetail && $outboundDetail->note ? json_decode($outboundDetail->note, true) : [];

        // Get extras from note
        $extras = $note['extras'] ?? [];
        $amenities = [];

        // Build amenities list based on extras
        if ($outboundDetail?->add_documentation) {
            $amenities[] = 'Dokumentasi';
        }
        if ($outboundDetail?->need_transportation) {
            $amenities[] = 'Transportasi';
        }

        // Build additional costs array
        $additionalCosts = [];

        if ($outboundDetail?->add_documentation && $outboundDetail->documentation_fee > 0) {
            $additionalCosts[] = [
                'name' => 'Dokumentasi',
                'price' => $this->formatIdr($outboundDetail->documentation_fee),
            ];
        }

        if ($outboundDetail?->need_transportation && $outboundDetail->transportation_fee > 0) {
            $additionalCosts[] = [
                'name' => 'Transportasi',
                'price' => $this->formatIdr($outboundDetail->transportation_fee),
            ];
        }

        // Format date
        $activityDate = $outboundDetail?->schedule_date;
        $formattedDate = $activityDate
            ? $activityDate->translatedFormat('l, d M Y')
            : '-';

        // Get outbound and variant names
        $outboundName = $outboundDetail?->outbound?->name ?? ($note['outbound_name'] ?? '-');
        $variantName = $outboundDetail?->outboundVariant?->variant_label ?? ($note['variant_name'] ?? null);

        return view('reservasi.detail-pesanan', [
            'token' => $token,
            'status' => $status,
            'booking' => $booking,
            'bookingType' => 'outbound',
            'name' => $booking->guest_name,
            'phone' => $booking->guest_phone,
            'email' => $booking->guest_email,
            'guestCount' => $outboundDetail?->total_participants ?? 0,
            'activityDate' => $formattedDate,
            'outboundName' => $outboundName,
            'variantName' => $variantName,
            'basePrice' => $this->formatIdr($outboundDetail?->subtotal ?? 0),
            'totalPrice' => $this->formatIdr($outboundDetail?->total_price ?? 0),
            'amenities' => $amenities,
            'additionalCosts' => $additionalCosts,
        ]);
    }

    /**
     * Update booking status (for payment flow).
     *
     * @param Request $request
     * @param string $token
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function updateBookingStatus(Request $request, string $token)
    {
        $booking = Booking::where('token_code', $token)->firstOrFail();

        $newStatus = $request->input('status');

        $validTransitions = [
            'proses' => ['pembayaran', 'berhasil', 'dibatalkan'], // 'berhasil' allowed for reschedule with no extra payment
            'pembayaran' => ['berhasil', 'dibatalkan'],
            'berhasil' => ['berjalan'],
            'berjalan' => ['selesai'],
        ];

        $currentStatus = $booking->status->value;

        if (!isset($validTransitions[$currentStatus]) || !in_array($newStatus, $validTransitions[$currentStatus])) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Invalid status transition.'], 400);
            }
            return back()->with('error', 'Invalid status transition.');
        }

        // If cancelling, delete the booking instead of updating status
        if ($newStatus === 'dibatalkan') {
            $cancelledBy = 'customer';

            DB::transaction(function () use ($booking, $cancelledBy) {
                // Save cancellation record for history
                Cancellation::create([
                    'booking_id' => $booking->id,
                    'cancellation_date' => now(),
                    'cancelled_by' => $cancelledBy,
                    'reason' => 'User cancelled order',
                    'status' => 'approved',
                    'cancellation_fee' => 0,
                    'total_refund' => 0,
                    'refund_status' => 'not_required',
                ]);

                // Delete related records
                $booking->bookingDetails()->delete();
                $booking->payments()->delete();
                $booking->bookingOutbounds()->delete();

                // Delete the booking
                $booking->delete();
            });

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Booking cancelled successfully.']);
            }
            return redirect()->route('reservasi.glamping')
                ->with('success', 'Booking cancelled successfully.');
        }

        // For other status updates, proceed normally
        $booking->status = BookingStatus::from($newStatus);
        $booking->save();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        }
        return redirect()->route('reservasi.detail-pesanan', ['token' => $token]);
    }

    /**
     * Show the reschedule page.
     * If code and email are provided via GET, lookup and show booking details.
     * Email must match the booking's guest email.
     */
    public function showReschedulePage(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
    {
        $code = $request->input('code');
        $email = $request->input('email');
        $booking = null;
        $error = null;

        if ($code && $email) {
            $booking = Booking::with([
                'bookingDetails.unit.area',
                'bookingOutbounds.outbound',
                'bookingOutbounds.outboundVariant',
            ])->where('token_code', strtoupper(trim($code)))->first();

            if (!$booking) {
                $error = 'Kode booking tidak ditemukan. Pastikan kode yang Anda masukkan benar.';
            } elseif (strtolower(trim($email)) !== strtolower(trim($booking->guest_email))) {
                $error = 'Email tidak sesuai dengan email pada booking ini. Silakan periksa kembali email Anda.';
                $booking = null;
            } elseif (!$booking->canBeRescheduled()) {
                $error = 'Booking dengan status "' . $booking->status->label() . '" tidak dapat di-reschedule.';
                $booking = null;
            }
        } elseif ($code || $email) {
            $error = 'Silakan masukkan kode booking dan email untuk melanjutkan.';
        }

        return view('reschedule', [
            'booking' => $booking,
            'error' => $error,
            'code' => $code,
            'email' => $email,
        ]);
    }

    /**
     * Show the reschedule form where guest can pick new dates/unit.
     * This page is styled like the glamping reservation page but is a separate flow.
     */
    public function showRescheduleForm(Request $request, string $token): \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
    {
        $booking = Booking::with(['bookingDetails.unit.area'])->where('token_code', strtoupper(trim($token)))->first();

        if (!$booking) {
            return redirect()->route('reschedule')->with('error', 'Kode booking tidak ditemukan.');
        }

        if ($booking->booking_type !== 'glamping') {
            return redirect()->route('reschedule')->with('error', 'Reschedule hanya tersedia untuk pemesanan Glamping melalui halaman ini.');
        }

        $detail = $booking->bookingDetails->first();

        if (!$booking->canBeRescheduled()) {
            return redirect()->route('reschedule')->with('error', 'Booking tidak dapat di-reschedule pada status saat ini.');
        }

        // Prepare reservation-like data (similar to showGlampingReservation)
        $checkinDate = $detail && $detail->check_in ? Carbon::parse($detail->check_in)->startOfDay() : Carbon::now()->startOfDay();
        $checkin = $checkinDate->format('Y-m-d');

        $areaUnits = $this->availabilityService->buildAreaUnits();
        $availability = $this->availabilityService->computeAvailabilityForDate($areaUnits, $checkin);
        $items = $this->getAmenityItems();
        $unitPrices = $this->pricingService->getUnitPricesBySeasonType();
        $highSeasonRanges = $this->pricingService->getHighSeasonRanges();
        $unitExtraCharges = $this->availabilityService->getUnitExtraCharges();

        /** @var User|null $authUser */
        $authUser = Auth::user();
        $contactPrefill = [
            'is_authenticated' => (bool) $authUser,
            'is_google' => (bool) ($authUser?->google_id),
            'name' => $authUser?->name,
            'email' => $authUser?->email,
            'country_code' => $authUser?->country_code,
            'phone' => $authUser?->phone,
        ];

        $originalTotalValue = $detail ? (float) $detail->total_price : 0.0;
        $originalTotalDisplay = $this->formatIdr($originalTotalValue);
        $rescheduleFee = $this->calculateRescheduleFee($booking);

        return view('reservasi.reschedule-form', [
            'availability' => $availability,
            'areaUnits' => $areaUnits,
            'items' => $items,
            'unitPrices' => $unitPrices,
            'highSeasonRanges' => $highSeasonRanges,
            'unitExtraCharges' => $unitExtraCharges,
            'checkinDefault' => $checkin,
            'contactPrefill' => $contactPrefill,
            'originalBooking' => $booking,
            'originalDetail' => $detail,
            'originalTotal' => $originalTotalValue,
            'originalTotalDisplay' => $originalTotalDisplay,
            'rescheduleFee' => $rescheduleFee,
        ]);
    }



    /**
     * Helper to calculate reschedule fee based on DAY_MARGIN.
     */
    private function calculateRescheduleFee(Booking $booking, Carbon $requestDate = null): float
    {
        $rescheduleFee = 0.0;
        $checkinDate = null;
        $basePrice = 0.0;
        $tentQuantity = 1;

        if ($booking->booking_type === 'glamping' && $booking->bookingDetails->first()) {
            $detail = $booking->bookingDetails->first();
            $checkinDate = $detail->check_in ? Carbon::parse($detail->check_in) : null;
            $basePrice = (float) $detail->total_price - (float) $detail->total_extra_charge;
        } elseif ($booking->booking_type === 'outbound' && $booking->bookingOutbounds->first()) {
            $out = $booking->bookingOutbounds->first();
            $checkinDate = $out->schedule_date ? Carbon::parse($out->schedule_date) : null;
            $basePrice = (float) $out->total_price;
        }

        if ($checkinDate && $basePrice > 0) {
            $now = $requestDate ? $requestDate->copy()->startOfDay() : Carbon::now('Asia/Jakarta')->startOfDay();
            $dDay = $checkinDate->copy()->startOfDay();
            $dayMargin = $now->diffInDays($dDay, false);

            if ($dayMargin > 7) {
                $rescheduleFee = 0.0;
            } elseif ($dayMargin >= 3 && $dayMargin <= 7) {
                $rescheduleFee = 150000.0 * $tentQuantity;
            } elseif ($dayMargin >= 1 && $dayMargin <= 2) {
                $rescheduleFee = 250000.0 * $tentQuantity;
            } else {
                $rescheduleFee = $basePrice * $tentQuantity;
            }
        }

        return $rescheduleFee;
    }

    /**
     * Process reschedule submission from guest.
     * Updates booking detail with new dates/unit and recalculates price.
     * If new price > old price: redirect to payment for difference
     * If new price <= old price: redirect to confirmed page
     */
    public function processReschedule(Request $request, string $token): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'checkin' => ['required', 'date', 'after_or_equal:today'],
            'checkout' => ['required', 'date', 'after:checkin'],
            'unit_id' => ['required', 'integer', 'exists:area_units,id'],
        ]);

        $booking = Booking::with(['bookingDetails.unit.area'])->where('token_code', strtoupper(trim($token)))->firstOrFail();

        if ($booking->booking_type !== 'glamping') {
            return redirect()->route('reschedule')->with('error', 'Reschedule hanya tersedia untuk Glamping di halaman ini.');
        }

        if (!$booking->canBeRescheduled()) {
            return redirect()->route('reschedule')->with('error', 'Booking tidak dapat di-reschedule pada status saat ini.');
        }

        $detail = $booking->bookingDetails->first();
        if (!$detail) {
            return redirect()->route('reschedule')->with('error', 'Rincian booking tidak ditemukan.');
        }

        // Use new guest count from request if provided, otherwise original
        $guestCount = $request->has('guestCount') ? (int) $request->input('guestCount') : (int) $detail->number_of_people;

        $unit = AreaUnit::query()->with(['area:id,slug,name,extra_charge_breakfast,extra_charge_full'])->findOrFail((int) $request->input('unit_id'));

        $checkin = Carbon::parse($request->input('checkin'))->startOfDay();
        $checkout = Carbon::parse($request->input('checkout'))->startOfDay();

        // Ensure availability
        $this->assertUnitAvailable($unit->id, $checkin, $checkout, $detail->id);

        // Recompute base price using PricingService
        $pricing = $this->pricingService->getUnitBasePriceForRange($unit->id, $checkin, $checkout);
        $basePrice = (float) ($pricing['total'] ?? 0.0);

        // Extract amenity quantities from form data if provided, otherwise fallback to original
        $note = $detail && $detail->note ? json_decode($detail->note, true) : [];
        $amenityBreakdown = [];
        $extraChargeAmenities = 0.0;

        if ($request->has('amenities')) {
            $amenityQuantities = [];
            foreach (($request->input('amenities') ?? []) as $itemId => $qty) {
                $qtyNum = (int) $qty;
                if ($qtyNum > 0) {
                    $amenityQuantities[(int) $itemId] = $qtyNum;
                }
            }
            if (!empty($amenityQuantities)) {
                $amenities = Item::with('prices')->whereIn('id', array_keys($amenityQuantities))->get();
                foreach ($amenities as $itemModel) {
                    $qty = $amenityQuantities[$itemModel->id] ?? 0;
                    $latest = $itemModel->prices->sortByDesc('created_at')->first();
                    $unitPrice = $latest ? (float) $latest->price : 0.0;
                    $lineTotal = $unitPrice * $qty;
                    $extraChargeAmenities += $lineTotal;
                    $amenityBreakdown[] = [
                        'id' => $itemModel->id,
                        'name' => $itemModel->name,
                        'type' => $itemModel->type,
                        'unit_price' => $unitPrice,
                        'qty' => $qty,
                        'line_total' => $lineTotal,
                    ];
                }
            }
        } else {
            // Reconstruct amenities from original note
            $amenitiesBreakdownOriginal = $note['amenities_breakdown'] ?? [];
            $amenityIds = array_values(array_filter(array_map(fn($i) => (int) ($i['id'] ?? 0), $amenitiesBreakdownOriginal)));
            if (!empty($amenityIds)) {
                $amenities = Item::with('prices')->whereIn('id', $amenityIds)->get();
            } else {
                $amenities = collect();
            }

            foreach ($amenitiesBreakdownOriginal as $itemRow) {
                $itemId = (int) ($itemRow['id'] ?? 0);
                $qty = max(0, (int) ($itemRow['qty'] ?? 0));
                if ($qty <= 0)
                    continue;
                $itemModel = $amenities->firstWhere('id', $itemId);
                $latest = $itemModel?->prices->sortByDesc('created_at')->first();
                $unitPrice = $latest ? (float) $latest->price : (float) ($itemRow['unit_price'] ?? 0.0);
                $lineTotal = $unitPrice * $qty;
                $extraChargeAmenities += $lineTotal;
                $amenityBreakdown[] = [
                    'id' => $itemId,
                    'name' => $itemRow['name'] ?? ($itemModel?->name ?? 'Item'),
                    'type' => $itemRow['type'] ?? ($itemModel?->type ?? null),
                    'unit_price' => $unitPrice,
                    'qty' => $qty,
                    'line_total' => $lineTotal,
                ];
            }
        }

        // Extra guest charge computation (use original or form provided mode)
        $extraChargeMode = (string) ($request->input('extra_charge_mode') ?? $note['extra_charge_mode'] ?? ($note['extra_breakfast']['mode'] ?? 'breakfast'));
        $defaultPeople = (int) ($unit->default_people ?? 0);
        $extraPeople = max(0, $guestCount - $defaultPeople);
        $fullRate = $unit->area && $unit->area->extra_charge_full !== null ? (float) $unit->area->extra_charge_full : 0.0;
        $breakfastRate = $unit->area && $unit->area->extra_charge_breakfast !== null ? (float) $unit->area->extra_charge_breakfast : 0.0;
        $selectedExtraRate = 0.0;
        if ($extraPeople > 0) {
            $selectedExtraRate = $extraChargeMode === 'full' ? $fullRate : $breakfastRate;
        }
        $extraChargeGuestMode = $extraPeople * $selectedExtraRate;

        $rescheduleFee = $this->calculateRescheduleFee($booking);

        $totalExtraCharge = $extraChargeAmenities + $extraChargeGuestMode + $rescheduleFee;
        $newTotalPrice = $basePrice + $totalExtraCharge;

        $originalTotal = (float) $detail->total_price;

        // Calculate price delta for payment routing
        $priceDelta = $newTotalPrice - $originalTotal;
        $paymentStatus = match (true) {
            $priceDelta > 0 => 'payment_required',
            $priceDelta < 0 => 'refund_due',
            default => 'no_payment_required',
        };

        // Capture original data before overwriting for reschedule info display
        $originalCheckin = $detail->check_in ? $detail->check_in->toDateString() : null;
        $originalCheckout = $detail->check_out ? $detail->check_out->toDateString() : null;
        $originalUnitId = (int) $detail->unit_id;
        $originalBasePrice = $originalTotal - (float) $detail->total_extra_charge;
        $originalExtraCharge = (float) $detail->total_extra_charge;
        $originalUnitName = $detail->unit?->name ?? '-';
        $originalAreaName = $detail->unit?->area?->name ?? '-';

        // Update DB inside transaction
        DB::transaction(function () use ($detail, $unit, $checkin, $checkout, $guestCount, $totalExtraCharge, $newTotalPrice, $amenityBreakdown, $extraPeople, $selectedExtraRate, $extraChargeMode, $fullRate, $breakfastRate, $booking, $originalTotal, $pricing, $defaultPeople, $extraChargeGuestMode, $basePrice, $priceDelta, $paymentStatus, $originalCheckin, $originalCheckout, $originalUnitId, $originalUnitName, $originalAreaName, $originalBasePrice, $originalExtraCharge, $rescheduleFee) {
            $detail->unit_id = $unit->id;
            $detail->check_in = $checkin->toDateString();
            $detail->check_out = $checkout->toDateString();
            $detail->number_of_people = $guestCount;
            $detail->total_extra_charge = $totalExtraCharge;

            $existingNote = json_decode($detail->note ?? '[]', true);
            if (!is_array($existingNote)) {
                $existingNote = [];
            }

            $merged = array_merge($existingNote, [
                'nights' => $pricing['nights'] ?? null,
                'nightly_breakdown' => $pricing['breakdown'] ?? [],
                'amenities' => array_map(fn($a) => $a['id'], $amenityBreakdown),
                'amenities_breakdown' => $amenityBreakdown,
                'extra_breakfast' => [
                    'default_people' => $defaultPeople,
                    'extra_people' => $extraPeople,
                    'rate' => $selectedExtraRate,
                    'amount' => $extraChargeGuestMode,
                    'mode' => $extraChargeMode,
                ],
                'extra_charge_rates' => [
                    'full' => $fullRate,
                    'breakfast' => $breakfastRate,
                ],
                'extra_charge_mode' => $extraChargeMode,
                // Reschedule comparison metadata for display in Order Details
                'reschedule_info' => [
                    'is_reschedule' => true,
                    'reschedule_date' => now()->toDateString(),
                    'payment_status' => $paymentStatus,
                    'price_delta' => $priceDelta,
                    'reschedule_fee' => $rescheduleFee,
                    'payable_amount' => abs($priceDelta),
                    'original' => [
                        'checkin' => $originalCheckin,
                        'checkout' => $originalCheckout,
                        'unit_id' => $originalUnitId,
                        'unit_name' => $originalUnitName,
                        'area_name' => $originalAreaName,
                        'base_price' => $originalBasePrice,
                        'extra_charge' => $originalExtraCharge,
                        'total_price' => $originalTotal,
                    ],
                    'new' => [
                        'checkin' => $checkin->toDateString(),
                        'checkout' => $checkout->toDateString(),
                        'unit_id' => $unit->id,
                        'unit_name' => $unit->name,
                        'area_name' => $unit->area?->name ?? '-',
                        'base_price' => $basePrice,
                        'extra_charge' => $totalExtraCharge,
                        'total_price' => $newTotalPrice,
                    ],
                ],
            ]);

            $detail->note = json_encode($merged, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            $detail->total_price = $newTotalPrice;

            $detail->save();

            // Selalu route ke PROSES terlebih dahulu agar user melakukan konfirmasi (review summary)
            $booking->status = BookingStatus::PROSES;
            $booking->save();
        });

        // Semua reschedule diarahkan ke halaman detail pesanan untuk konfirmasi (tahap booking)
        if ($paymentStatus === 'payment_required') {
            $msg = 'Silakan periksa detail reschedule dan lanjutkan pembayaran untuk selisih harga.';
        } elseif ($paymentStatus === 'refund_due') {
            $msg = 'Silakan konfirmasi detail reschedule Anda. Anda akan menerima panduan untuk pengajuan refund setelah konfirmasi.';
        } else {
            $msg = 'Silakan periksa dan konfirmasi detail reschedule Anda.';
        }

        return redirect()->route('reservasi.detail-pesanan', ['token' => $booking->token_code])
            ->with('info', $msg);
    }

    /**
     * Show the cancellation page.
     * If code and email are provided via GET, lookup and show booking details.
     * Email must match the booking's guest email.
     */
    public function showCancellationPage(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
    {
        $code = $request->input('code');
        $email = $request->input('email');
        $booking = null;
        $error = null;

        if ($code && $email) {
            $booking = Booking::with([
                'bookingDetails.unit.area',
                'bookingOutbounds.outbound',
                'bookingOutbounds.outboundVariant',
            ])->where('token_code', strtoupper(trim($code)))->first();

            if (!$booking) {
                $error = 'Kode booking tidak ditemukan. Pastikan kode yang Anda masukkan benar.';
            } elseif (strtolower(trim($email)) !== strtolower(trim($booking->guest_email))) {
                $error = 'Email tidak sesuai dengan email pada booking ini. Silakan periksa kembali email Anda.';
                $booking = null;
            } elseif (!$booking->canBeCancelled()) {
                $error = 'Booking dengan status "' . $booking->status->label() . '" tidak dapat dibatalkan.';
                $booking = null;
            }
        } elseif ($code || $email) {
            $error = 'Silakan masukkan kode booking dan email untuk melanjutkan.';
        }

        return view('cancellation', [
            'booking' => $booking,
            'error' => $error,
            'code' => $code,
            'email' => $email,
        ]);
    }

    /**
     * Helper to calculate cancellation fee and refund amount based on DAY_MARGIN.
     */
    private function calculateCancellationFeeAndRefund(Booking $booking): array
    {
        $cancellationFee = 0.0;
        $refundAmount = 0.0;
        $isPaid = $booking->status === BookingStatus::BERHASIL;

        if ($isPaid) {
            $checkinDate = null;
            $totalPrice = 0.0;

            if ($booking->booking_type === 'glamping') {
                $detail = $booking->bookingDetails()->first();
                $checkinDate = $detail->check_in ? Carbon::parse($detail->check_in) : null;
                $totalPrice = (float) ($detail->total_price ?? 0.0);
            } else if ($booking->booking_type === 'outbound') {
                $out = $booking->bookingOutbounds()->first();
                $checkinDate = $out->schedule_date ? Carbon::parse($out->schedule_date) : null;
                $totalPrice = (float) ($out->total_price ?? 0.0);
            }

            if ($checkinDate) {
                $checkinDate = $checkinDate->startOfDay();
                $now = Carbon::now('Asia/Jakarta')->startOfDay();
                $dDay = $checkinDate->copy()->startOfDay();
                $dayMargin = $now->diffInDays($dDay, false);

                if ($dayMargin > 7) {
                    $cancellationFeePct = 0.25;
                } elseif ($dayMargin >= 4 && $dayMargin <= 7) {
                    $cancellationFeePct = 0.50;
                } elseif ($dayMargin >= 2 && $dayMargin <= 3) {
                    $cancellationFeePct = 0.75;
                } else {
                    $cancellationFeePct = 1.00;
                }

                $cancellationFee = $totalPrice * $cancellationFeePct;
                $refundAmount = max(0.0, $totalPrice - $cancellationFee);
            }
        }

        return [
            'fee' => $cancellationFee,
            'refund' => $refundAmount,
            'is_paid' => $isPaid,
        ];
    }

    /**
     * Show confirmation page that summarizes refund and booking details.
     */
    public function showCancellationConfirmPage(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
    {
        $code = $request->input('code');
        if (!$code) {
            return redirect()->route('cancellation')->with('error', 'Kode booking tidak disediakan.');
        }

        $booking = Booking::with([
            'bookingDetails.unit.area',
            'bookingOutbounds.outbound',
            'bookingOutbounds.outboundVariant',
        ])->where('token_code', strtoupper(trim($code)))->first();

        if (!$booking) {
            return redirect()->route('cancellation')->with('error', 'Kode booking tidak ditemukan.');
        }

        if (!$booking->canBeCancelled()) {
            return redirect()->route('cancellation')->with('error', 'Booking dengan status "' . $booking->status->label() . '" tidak dapat dibatalkan.');
        }

        $calc = $this->calculateCancellationFeeAndRefund($booking);
        $cancellationFee = $calc['fee'];
        $refundAmount = $calc['refund'];
        $isPaid = $calc['is_paid'];

        return view('cancellation_confirm', [
            'booking' => $booking,
            'code' => strtoupper(trim($code)),
            'cancellation_fee' => round($cancellationFee, 0),
            'refund_amount' => round($refundAmount, 0),
            'is_paid' => $isPaid,
        ]);
    }

    /**
     * Process refund after user confirms on the confirmation page.
     */
    public function processRefund(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'reason' => 'nullable|string|max:500',
            'accept_terms' => 'accepted',
        ]);

        $code = strtoupper(trim($request->input('code')));
        $booking = Booking::with(['bookingDetails', 'bookingOutbounds'])->where('token_code', $code)->first();

        if (!$booking) {
            return redirect()->route('cancellation')->with('error', 'Kode booking tidak ditemukan.');
        }

        if (!$booking->canBeCancelled()) {
            return redirect()->route('cancellation')->with('error', 'Booking dengan status "' . $booking->status->label() . '" tidak dapat dibatalkan.');
        }

        $calc = $this->calculateCancellationFeeAndRefund($booking);
        $cancellationFee = $calc['fee'];
        $refundAmount = $calc['refund'];
        $isPaid = $calc['is_paid'];
        $refundStatus = ($isPaid && $refundAmount > 0) ? 'completed' : 'not_required';

        DB::transaction(function () use ($booking, $request, $cancellationFee, $refundAmount, $refundStatus) {
            Cancellation::create([
                'booking_id' => $booking->id,
                'cancellation_date' => now(),
                'cancelled_by' => Auth::check() ? 'user' : 'guest',
                'reason' => $request->input('reason', 'User requested cancellation'),
                'status' => 'approved',
                'cancellation_fee' => $cancellationFee,
                'total_refund' => $refundAmount,
                'refund_status' => $refundStatus,
            ]);

            $booking->status = BookingStatus::DIBATALKAN;
            $booking->save();

            // TODO: enqueue refund job / integrate payment gateway if required
        });

        return redirect()->route('cancellation.success', ['code' => $booking->token_code])->with('success', 'Pembatalan dan pengembalian dana berhasil diproses.');
    }

    /**
     * Show a simple success page after refund + cancellation.
     */
    public function showCancellationSuccessPage(Request $request): \Illuminate\Contracts\View\View
    {
        $code = $request->input('code');
        $isPaid = false;
        $refundAmount = 0;
        $cancellationFee = 0;

        if ($code) {
            $booking = Booking::with([
                'bookingDetails.unit.area',
                'bookingOutbounds.outbound',
            ])->where('token_code', strtoupper(trim($code)))->first();

            if ($booking) {
                $calc = $this->calculateCancellationFeeAndRefund($booking);
                $cancellationFee = $calc['fee'];
                $refundAmount = $calc['refund'];
                $isPaid = $calc['is_paid'];
            }
        }

        return view('cancellation_success', [
            'code' => $code,
            'refund_amount' => round($refundAmount, 0),
            'cancellation_fee' => round($cancellationFee, 0),
            'is_paid' => $isPaid,
        ]);
    }

    /**
     * Process booking cancellation for guest users.
     */
    public function processCancellation(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'reason' => 'nullable|string|max:500',
        ]);

        $code = strtoupper(trim($request->input('code')));
        $booking = Booking::where('token_code', $code)->first();

        if (!$booking) {
            return back()->with('error', 'Kode booking tidak ditemukan.');
        }

        if (!$booking->canBeCancelled()) {
            return back()->with('error', 'Booking dengan status "' . $booking->status->label() . '" tidak dapat dibatalkan.');
        }

        $calc = $this->calculateCancellationFeeAndRefund($booking);
        $cancellationFee = $calc['fee'];
        $refundAmount = $calc['refund'];
        $isPaid = $calc['is_paid'];
        $refundStatus = ($isPaid && $refundAmount > 0) ? 'completed' : 'not_required';

        DB::transaction(function () use ($booking, $request, $cancellationFee, $refundAmount, $refundStatus) {
            // Save cancellation record
            Cancellation::create([
                'booking_id' => $booking->id,
                'cancellation_date' => now(),
                'cancelled_by' => 'guest',
                'reason' => $request->input('reason', 'Guest requested cancellation'),
                'status' => 'approved',
                'cancellation_fee' => $cancellationFee,
                'total_refund' => $refundAmount,
                'refund_status' => $refundStatus,
            ]);

            // Update booking status
            $booking->status = BookingStatus::DIBATALKAN;
            $booking->save();
        });

        return redirect()->route('cancellation')
            ->with('success', 'Booking berhasil dibatalkan. Terima kasih telah menghubungi kami.');
    }

    /**
     * Estimate reschedule pricing breakdown (API endpoint).
     * Calculates the new price for selected dates/unit without saving to DB.
     * Returns breakdown: original_price, seasonal_charge, reschedule_fee, extra_items, total.
     */
    public function estimateReschedulePrice(Request $request, string $token): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'checkin' => ['required', 'date'],
            'checkout' => ['required', 'date', 'after:checkin'],
            'unit_id' => ['required', 'integer', 'exists:area_units,id'],
            'guest_count' => ['nullable', 'integer', 'min:1'],
            'extra_items' => ['nullable', 'array'],
            'extra_items.*' => ['integer', 'min:0'],
        ]);

        try {
            // Get the original booking
            $booking = Booking::with(['bookingDetails.unit.area'])
                ->where('token_code', strtoupper(trim($token)))
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak ditemukan.',
                ], Response::HTTP_NOT_FOUND);
            }

            if ($booking->booking_type !== 'glamping') {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking bukan tipe glamping.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $originalDetail = $booking->bookingDetails->first();
            if (!$originalDetail) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rincian booking tidak ditemukan.',
                ], Response::HTTP_NOT_FOUND);
            }

            // Parse new dates
            $newCheckin = Carbon::parse($request->input('checkin'))->startOfDay();
            $newCheckout = Carbon::parse($request->input('checkout'))->startOfDay();
            $newUnitId = (int) $request->input('unit_id');

            // Get the new unit
            $newUnit = AreaUnit::query()
                ->with(['area:id,slug,extra_charge_breakfast,extra_charge_full'])
                ->findOrFail($newUnitId);

            // Validate availability
            if (
                !$this->availabilityService->isUnitAvailable(
                    $newUnit->id,
                    $newCheckin->toDateString(),
                    $newCheckout->toDateString(),
                    $originalDetail->id
                )
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unit tidak tersedia untuk tanggal yang dipilih.',
                ], Response::HTTP_CONFLICT);
            }

            // Original pricing
            $originalPrice = (float) $originalDetail->total_price;

            // Calculate new base price
            $newPricing = $this->pricingService->getUnitBasePriceForRange(
                $newUnit->id,
                $newCheckin,
                $newCheckout
            );
            $newBasePrice = (float) ($newPricing['total'] ?? 0.0);

            // Reconstruct amenities from original note
            $note = $originalDetail && $originalDetail->note
                ? json_decode($originalDetail->note, true)
                : [];
            $amenitiesBreakdown = $note['amenities_breakdown'] ?? [];
            $requestExtraItems = $request->input('extra_items', []);

            $amenitiesResult = $this->calculateRescheduleAmenities($amenitiesBreakdown, $requestExtraItems);
            $finalAmenityBreakdown = $amenitiesResult['breakdown'];
            $finalExtraChargeAmenities = $amenitiesResult['total_charge'];

            // Handle guest count: use new value from request or keep original
            $guestCount = (int) ($request->input('guest_count') ?? $originalDetail->number_of_people);

            // Validate new guest count against unit capacity
            if ($guestCount > (int) $newUnit->max_people) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah tamu melebihi kapasitas unit (' . $newUnit->max_people . ' orang).',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $defaultPeople = (int) ($newUnit->default_people ?? 0);
            $extraPeople = max(0, $guestCount - $defaultPeople);

            $fullRate = $newUnit->area && $newUnit->area->extra_charge_full !== null
                ? (float) $newUnit->area->extra_charge_full
                : 0.0;
            $breakfastRate = $newUnit->area && $newUnit->area->extra_charge_breakfast !== null
                ? (float) $newUnit->area->extra_charge_breakfast
                : 0.0;

            $reqExtraChargeMode = $request->input('extra_charge_mode');
            $extraChargeMode = $reqExtraChargeMode ? (string) $reqExtraChargeMode : (string) ($note['extra_charge_mode'] ?? ($note['extra_breakfast']['mode'] ?? 'breakfast'));
            $selectedExtraRate = 0.0;

            if ($extraPeople > 0) {
                $selectedExtraRate = $extraChargeMode === 'full' ? $fullRate : $breakfastRate;
            }

            $extraChargeGuestMode = $extraPeople * $selectedExtraRate;

            $rescheduleFee = $this->calculateRescheduleFee($booking);

            $totalNewExtraCharge = $finalExtraChargeAmenities + $extraChargeGuestMode + $rescheduleFee;
            $newTotalPrice = $newBasePrice + $totalNewExtraCharge;

            // Calculate original breakdown
            $originalBasePrice = (float) $originalDetail->total_price - (float) $originalDetail->total_extra_charge;
            $originalExtraCharge = (float) $originalDetail->total_extra_charge;

            // DELTA PAYMENT MODEL
            // Guest only pays/refunds the DIFFERENCE, not the full new price
            $priceDelta = $newTotalPrice - $originalPrice;

            // Calculate breakdown of what changed
            $basePrice_Delta = $newBasePrice - $originalBasePrice;
            $extraCharge_Delta = $totalNewExtraCharge - $originalExtraCharge;

            // Determine payment status
            $paymentStatus = match (true) {
                $priceDelta > 0 => 'payment_required',
                $priceDelta < 0 => 'refund_due',
                default => 'no_payment_required',
            };

            $breakdown = [
                'original_price' => $originalPrice,
                'new_price' => $newTotalPrice,
                'price_difference' => $priceDelta,
                'payable_amount' => abs($priceDelta),
                'payment_status' => $paymentStatus,
                'is_price_increase' => $priceDelta > 0,
                'is_price_decrease' => $priceDelta < 0,
                'reschedule_fee' => $rescheduleFee,
            ];

            $breakdownDetail = [
                'original' => [
                    'base_price' => $originalBasePrice,
                    'extra_charge' => $originalExtraCharge,
                    'total' => $originalPrice,
                ],
                'new' => [
                    'base_price' => $newBasePrice,
                    'extra_charge' => $totalNewExtraCharge,
                    'total' => $newTotalPrice,
                ],
                'delta' => [
                    'base_price_change' => $basePrice_Delta,
                    'extra_charge_change' => $extraCharge_Delta,
                    'total_change' => $priceDelta,
                ],
            ];

            $details = [
                'original_dates' => [
                    'checkin' => $originalDetail->check_in->toDateString(),
                    'checkout' => $originalDetail->check_out->toDateString(),
                    'nights' => $originalDetail->check_out->diffInDays($originalDetail->check_in),
                ],
                'new_dates' => [
                    'checkin' => $newCheckin->toDateString(),
                    'checkout' => $newCheckout->toDateString(),
                    'nights' => $newCheckout->diffInDays($newCheckin),
                ],
                'guest' => [
                    'original_count' => (int) $originalDetail->number_of_people,
                    'new_count' => $guestCount,
                    'extra_people' => $extraPeople,
                    'extra_charge_mode' => $extraChargeMode,
                    'extra_charge_amount' => $extraChargeGuestMode,
                ],
                'amenities' => $finalAmenityBreakdown,
                'extra_charges_breakdown' => [
                    'amenities' => $finalExtraChargeAmenities,
                    'guest_extra' => $extraChargeGuestMode,
                    'total' => $totalNewExtraCharge,
                ],
            ];

            return response()->json([
                'success' => true,
                'breakdown' => $breakdown,
                'breakdown_detail' => $breakdownDetail,
                'details' => $details,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengestimasi harga: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function calculateRescheduleAmenities(array $amenitiesBreakdown, array $requestExtraItems): array
    {
        // 1. Get original items
        $amenityIds = [];
        foreach ($amenitiesBreakdown as $item) {
            if (!empty($item['id'])) {
                $amenityIds[] = (int) $item['id'];
            }
        }
        $amenityIds = array_unique($amenityIds);

        /** @var \Illuminate\Database\Eloquent\Collection $amenities */
        $amenities = !empty($amenityIds)
            ? Item::with('prices')->whereIn('id', $amenityIds)->get()
            : collect();

        $newAmenityBreakdown = [];
        $extraChargeAmenities = 0.0;

        foreach ($amenitiesBreakdown as $itemRow) {
            $itemId = (int) ($itemRow['id'] ?? 0);
            $qty = max(0, (int) ($itemRow['qty'] ?? 0));
            if ($qty <= 0)
                continue;

            $itemModel = $amenities->firstWhere('id', $itemId);
            $latest = $itemModel?->prices->sortByDesc('created_at')->first();
            $unitPrice = $latest ? (float) $latest->price : ($itemModel ? (float) $itemModel->price : (float) ($itemRow['unit_price'] ?? 0.0));
            $lineTotal = $unitPrice * $qty;
            $extraChargeAmenities += $lineTotal;

            $newAmenityBreakdown[] = [
                'id' => $itemId,
                'name' => $itemRow['name'] ?? ($itemModel?->name ?? 'Item'),
                'qty' => $qty,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
            ];
        }

        if (empty($requestExtraItems)) {
            return [
                'breakdown' => $newAmenityBreakdown,
                'total_charge' => $extraChargeAmenities,
            ];
        }

        // 2. Handle request overrides
        $finalExtraChargeAmenities = 0.0;
        $updatedAmenities = [];

        $allItemIds = [];
        foreach ($newAmenityBreakdown as $item) {
            if (isset($item['id'])) {
                $allItemIds[] = (int) $item['id'];
            }
        }
        foreach ($requestExtraItems as $reqId => $reqQty) {
            $allItemIds[] = (int) $reqId;
        }
        $allItemIds = array_values(array_unique($allItemIds));

        /** @var \Illuminate\Database\Eloquent\Collection $items */
        $items = !empty($allItemIds) ? Item::with('prices')->whereIn('id', $allItemIds)->get() : collect();

        foreach ($allItemIds as $itemId) {
            $originalItem = collect($newAmenityBreakdown)->firstWhere('id', $itemId);
            $newQty = isset($requestExtraItems[$itemId]) ? max(0, (int) $requestExtraItems[$itemId]) : ($originalItem['qty'] ?? 0);

            if ($newQty > 0) {
                $itemModel = $items->firstWhere('id', $itemId);
                $latest = $itemModel?->prices->sortByDesc('created_at')->first();
                $unitPrice = $latest ? (float) $latest->price : ($itemModel ? (float) $itemModel->price : (float) ($originalItem['unit_price'] ?? 0.0));

                $lineTotal = $unitPrice * $newQty;
                $finalExtraChargeAmenities += $lineTotal;

                $updatedAmenities[] = [
                    'id' => $itemId,
                    'name' => $originalItem['name'] ?? ($itemModel?->name ?? 'Item'),
                    'qty' => $newQty,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ];
            }
        }

        return [
            'breakdown' => $updatedAmenities,
            'total_charge' => $finalExtraChargeAmenities,
        ];
    }
}
