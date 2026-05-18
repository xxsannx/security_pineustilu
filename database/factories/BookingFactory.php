<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'booking_type' => 'glamping',
            'booking_date' => $this->faker->date(),
            'token_code' => strtoupper(Str::random(10)),
            'status' => BookingStatus::PROSES,
            'guest_name' => $this->faker->name(),
            'guest_phone' => $this->faker->phoneNumber(),
            'guest_email' => $this->faker->safeEmail(),
        ];
    }

    /**
     * Configure the booking with PEMBAYARAN status.
     */
    public function pendingPayment(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::PEMBAYARAN,
        ]);
    }

    /**
     * Configure the booking with BERHASIL status.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::BERHASIL,
        ]);
    }

    /**
     * Configure the booking with BERJALAN status.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::BERJALAN,
        ]);
    }

    /**
     * Configure the booking with SELESAI status.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::SELESAI,
        ]);
    }

    /**
     * Configure the booking with DIBATALKAN status.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::DIBATALKAN,
        ]);
    }
}
