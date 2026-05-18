<?php

namespace Tests\Feature;

use App\Models\AreaUnit;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Tests\TestCase;

class GlampingReservationFlowTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_reservasi_glamping_page_loads(): void
    {
        $this->seed();

        $response = $this->get(route('reservasi.glamping'));

        $response->assertOk();
        $response->assertSee('Glamping Reservation', false);
        $response->assertSee('name="checkin"', false);
        $response->assertSee('name="checkout"', false);
    }

    public function test_can_create_booking_and_prevent_overlap(): void
    {
        $this->seed();

        $unit = AreaUnit::query()->orderBy('id')->first();
        $this->assertNotNull($unit, 'Expected AreaUnit to be seeded');

        $amenity = Item::query()->orderBy('id')->first();

        $checkin = Carbon::now()->startOfDay();
        $checkout = $checkin->copy()->addDay();

        $payload = [
            'unit_id' => $unit->id,
            'checkin' => $checkin->toDateString(),
            'checkout' => $checkout->toDateString(),
            'guestCount' => 1,
            'name' => 'Test Guest',
            'phone' => '081234567890',
            'email' => 'guest@example.com',
            'agree' => 'on',
        ];

        if ($amenity) {
            $payload['amenities'] = [$amenity->id];
        }

        // Web routes include CSRF; disable for feature smoke test.
        $response = $this->withoutMiddleware()->post(route('reservasi.glamping.store'), $payload);

        // Successful booking redirects to detail-pesanan page
        $this->assertDatabaseCount('bookings', 1);
        $booking = Booking::query()->first();
        $this->assertNotNull($booking);
        $response->assertRedirect(route('reservasi.detail-pesanan', ['token' => $booking->token_code]));

        $this->assertDatabaseCount('booking_details', 1);

        $detail = BookingDetail::query()->first();

        $this->assertNotNull($detail);

        $this->assertSame('glamping', $booking->booking_type);
        $this->assertTrue(Str::length((string) $booking->token_code) >= 10);

        $this->assertSame($unit->id, $detail->unit_id);
        $this->assertSame($checkin->toDateString(), Carbon::parse($detail->check_in)->toDateString());
        $this->assertSame($checkout->toDateString(), Carbon::parse($detail->check_out)->toDateString());
        $this->assertGreaterThanOrEqual(0, (float) $detail->total_extra_charge);
        $this->assertGreaterThan(0, (float) $detail->total_price);

        // Second booking on same unit + overlapping dates should fail.
        $response2 = $this
            ->from(route('reservasi.glamping'))
            ->withoutMiddleware()
            ->post(route('reservasi.glamping.store'), $payload);

        $response2->assertRedirect(route('reservasi.glamping'));
        $response2->assertSessionHasErrors(['unit_id']);

        $this->assertDatabaseCount('bookings', 1);
        $this->assertDatabaseCount('booking_details', 1);
    }
}
