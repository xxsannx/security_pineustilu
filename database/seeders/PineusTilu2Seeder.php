<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Area;
use App\Helpers\FacilityIconHelper;

class PineusTilu2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get areas
        $pineusTilu2 = Area::where('name', 'Pineus Tilu 2')->first();

        if (!$pineusTilu2) {
            $this->command->error('Area Pineus Tilu 2 not found. Please run AreaSeeder first.');
            return;
        }

        $facilities = [];

        // Private Facilities
        $facilities[] = [
            'area_id' => $pineusTilu2->id,
            'name' => '4 Foam Mattresses',
            'type' => 'private',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu2->id,
            'name' => '4 Pillows',
            'type' => 'private',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu2->id,
            'name' => '4 Sleeping Bags',
            'type' => 'private',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu2->id,
            'name' => '4 Breakfasts',
            'type' => 'private',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu2->id,
            'name' => '4 Floor Cushions',
            'type' => 'private',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu2->id,
            'name' => 'Private Dining Table with 4 Floor Cushions',
            'type' => 'private',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu2->id,
            'name' => 'Power Outlet',
            'type' => 'private',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu2->id,
            'name' => 'Indoor & Outdoor Lights',
            'type' => 'private',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu2->id,
            'name' => 'Net Hammock',
            'type' => 'private',
            'icon' => null,
            'description' => '(Deck 7, 8 & 9)',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu2->id,
            'name' => 'Coffee Table',
            'type' => 'private',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu2->id,
            'name' => 'Bamboo Mat',
            'type' => 'private',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        
        // Public Facilities
        $facilities[] = [
            'area_id' => $pineusTilu2->id,
            'name' => 'Bathroom with Hot Water',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu2->id,
            'name' => 'Drinking Water & Dispenser',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu2->id,
            'name' => 'Large Communal Table',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu2->id,
            'name' => 'Bonfire + Bonfire Grill',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu2->id,
            'name' => 'Children\'s Play Pool',
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

        $this->command->info('Facilities for Pineus Tilu 2 seeded successfully!');
    }
}