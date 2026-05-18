<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SeasonDateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seasons = [
            // Weekday Season (Sunday - Thursday)
            [
                'season_type' => 'weekday',
                'start_date' => '2025-01-01',
                'end_date' => '2026-12-31',
                'description' => 'Sunday - Thursday (Normal Price)',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            // Weekend Season (Friday - Saturday)
            [
                'season_type' => 'weekend',
                'start_date' => '2025-01-01',
                'end_date' => '2026-12-31',
                'description' => 'Friday - Saturday (Weekend Price)',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // High Season
            [
                'season_type' => 'high_season',
                'start_date' => '2025-12-20',
                'end_date' => '2026-03-28',
                'description' => 'All high season dates (for pricing)',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Ramadan Promo
            [
                'season_type' => 'ramadan_weekday',
                'start_date' => '2026-02-18',
                'end_date' => '2026-03-18',
                'description' => 'Ramadan Promo - Sunday to Thursday',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            [
                'season_type' => 'ramadan_weekend',
                'start_date' => '2026-02-18',
                'end_date' => '2026-03-18',
                'description' => 'Ramadan Promo - Friday to Saturday',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Upsert by season_type to avoid duplicates
        foreach ($seasons as $s) {
            DB::table('season_dates')->updateOrInsert(
                ['season_type' => $s['season_type']],
                [
                    'start_date' => $s['start_date'],
                    'end_date' => $s['end_date'],
                    'description' => $s['description'] ?? null,
                    'updated_at' => Carbon::now(),
                ]
            );
        }
    }
}