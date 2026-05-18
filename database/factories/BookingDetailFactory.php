<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\AreaUnit;
use App\Models\BookingDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookingDetail>
 */
class BookingDetailFactory extends Factory
{
    protected $model = BookingDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $checkin = $this->faker->dateTimeBetween('+1 day', '+30 days');
        $checkout = (clone $checkin)->modify('+1 day');

        return [
            'booking_id' => Booking::factory(),
            'unit_id' => AreaUnit::query()->inRandomOrder()->first()?->id ?? 1,
            'check_in' => $checkin->format('Y-m-d'),
            'check_out' => $checkout->format('Y-m-d'),
            'number_of_people' => $this->faker->numberBetween(1, 4),
            'total_extra_charge' => $this->faker->randomFloat(2, 0, 500000),
            'total_price' => $this->faker->randomFloat(2, 500000, 2000000),
            'note' => json_encode(['test' => true]),
        ];
    }
}
