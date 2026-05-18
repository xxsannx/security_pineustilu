<?php

namespace Database\Factories;

use App\Models\SeasonDate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SeasonDate>
 */
class SeasonDateFactory extends Factory
{
    protected $model = SeasonDate::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('+1 month', '+3 months');
        $endDate = (clone $startDate)->modify('+7 days');

        return [
            'season_type' => $this->faker->randomElement(['weekday', 'weekend', 'high_season']),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'description' => $this->faker->sentence(),
        ];
    }

    /**
     * High season state.
     */
    public function highSeason(): static
    {
        return $this->state(fn (array $attributes) => [
            'season_type' => 'high_season',
        ]);
    }

    /**
     * Weekend state.
     */
    public function weekend(): static
    {
        return $this->state(fn (array $attributes) => [
            'season_type' => 'weekend',
        ]);
    }

    /**
     * Weekday state.
     */
    public function weekday(): static
    {
        return $this->state(fn (array $attributes) => [
            'season_type' => 'weekday',
        ]);
    }
}
