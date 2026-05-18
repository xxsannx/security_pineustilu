<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AreaUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Safer seeding: resolve area IDs by slug and run inside a transaction
        DB::transaction(function () {
            $now = Carbon::now();
            $areas = DB::table('areas')->pluck('id', 'slug')->toArray();

            $units = [];

            // Helper closure to push unit rows
            $add = function ($areaSlug, $name, $defaultPeople, $maxPeople, $tentType) use (&$units, $areas, $now) {
                $areaId = $areas[$areaSlug] ?? null;
                if (!$areaId) return;
                $units[] = [
                    'area_id' => $areaId,
                    'name' => $name,
                    'default_people' => $defaultPeople,
                    'max_people' => $maxPeople,
                    'tent_type' => $tentType,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            };

            // Pineus Tilu 1
            foreach ([1, 2, 8, 9] as $deckNumber) {
                $add('pineus-tilu-1', "Deck {$deckNumber}", 4, 6, 'Tent 4.2');
            }
            foreach ([3, 4, 5, 6, 7] as $deckNumber) {
                $add('pineus-tilu-1', "Deck {$deckNumber}", 4, 6, 'Tent 4.0');
            }

            // Pineus Tilu 2
            foreach ([1, 2, 3, 4, 5, 6] as $deckNumber) {
                $add('pineus-tilu-2', "Deck {$deckNumber}", 4, 6, 'Tent 4.0');
            }
            foreach ([7, 8, 9] as $deckNumber) {
                $add('pineus-tilu-2', "Deck {$deckNumber}", 4, 6, 'Tent 4.2');
            }

            // Pineus Tilu 3 VIP
            foreach ([1, 2, 3, 4, 5] as $deckNumber) {
                $add('pineus-tilu-3-vip', "Deck {$deckNumber}", 6, 10, 'Tent 6.3');
            }
            foreach ([6, 7, 8, 9] as $deckNumber) {
                $add('pineus-tilu-3-vip', "Deck {$deckNumber}", 5, 9, 'Tent 5.2');
            }

            // Pineus Tilu 4 - plots
            for ($plotNumber = 1; $plotNumber <= 21; $plotNumber++) {
                $add('pineus-tilu-4', "Plot {$plotNumber}", 4, 6, 'Tent 4.2');
            }

            // Cabin
            $add('pineus-tilu-cabin', 'Cabin', 2, 5, 'Cabin');
            // Cabin VVIP
            $add('pineus-tilu-cabin-vvip', 'Cabin VVIP', 4, 8, 'Cabin VVIP');

            if (!empty($units)) {
                DB::table('area_units')->insert($units);
            }
        });
    }
}