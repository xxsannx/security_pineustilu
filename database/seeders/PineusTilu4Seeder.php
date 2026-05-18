<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Area;
use App\Helpers\FacilityIconHelper;

class PineusTilu4Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Pineus Tilu 4 area
        $pineusTilu4 = Area::where('name', 'Pineus Tilu 4')->first();

        if (!$pineusTilu4) {
            $this->command->error('Area Pineus Tilu 4 not found. Please run AreaSeeder first.');
            return;
        }

        $facilities = [];

        // ========== PRIVATE FACILITIES ==========
        $facilities[] = [
            'area_id' => $pineusTilu4->id,
            'name' => '4 Foam Mattresses',
            'type' => 'private',
            'icon' => null,
            'description' => 'Facilities for 4 people includes',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu4->id,
            'name' => '4 Pillows',
            'type' => 'private',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu4->id,
            'name' => '4 Sleeping Bags',
            'type' => 'private',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu4->id,
            'name' => '4 Breakfasts',
            'type' => 'private',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu4->id,
            'name' => 'Private Dining Table with 6 Benches',
            'type' => 'private',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu4->id,
            'name' => 'Coffee Table',
            'type' => 'private',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu4->id,
            'name' => 'Power Outlet',
            'type' => 'private',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu4->id,
            'name' => 'Indoor & Outdoor Lamp',
            'type' => 'private',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu4->id,
            'name' => 'All Tents Use Type 4.2',
            'type' => 'private',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu4->id,
            'name' => 'Standing Hammock',
            'type' => 'private',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu4->id,
            'name' => 'Bamboo Mat',
            'type' => 'private',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu4->id,
            'name' => 'Console Table',
            'type' => 'private',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // ========== PUBLIC FACILITIES ==========
        $facilities[] = [
            'area_id' => $pineusTilu4->id,
            'name' => 'Bathroom with Water Heater',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu4->id,
            'name' => '3 Bonfires + Bonfire Grill',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu4->id,
            'name' => 'Drinking Water & Dispenser',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu4->id,
            'name' => '4 Shared Dining Tables',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu4->id,
            'name' => '13 Hammocks',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu4->id,
            'name' => '28 Coffee Tables',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu4->id,
            'name' => 'Wifi',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu4->id,
            'name' => 'CCTV',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // Assign icons using FacilityIconHelper
        FacilityIconHelper::assignIcons($facilities);

        DB::transaction(function () use ($facilities) {
            foreach ($facilities as $fac) {
                DB::table('facilities')->updateOrInsert(
                    ['area_id' => $fac['area_id'], 'name' => $fac['name']],
                    [
                        'type' => $fac['type'] ?? null,
                        'icon' => $fac['icon'] ?? null,
                        'description' => $fac['description'] ?? null,
                        'updated_at' => $fac['updated_at'] ?? now(),
                    ]
                );
            }
        });

        $this->command->info('Facilities for Pineus Tilu 4 seeded successfully!');
    }
}