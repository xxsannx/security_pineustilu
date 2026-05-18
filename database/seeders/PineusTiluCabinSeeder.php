<?php

namespace Database\Seeders;

use App\Helpers\FacilityIconHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Area;

class PineusTiluCabinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Pineus Tilu Cabin area
        $pineusTiluCabin = Area::where('name', 'Pineus Tilu Cabin')->first();

        if (!$pineusTiluCabin) {
            $this->command->error('Area Pineus Tilu Cabin not found. Please run AreaSeeder first.');
            return;
        }

        $facilities = [];

        // ========== PRIVATE FACILITIES ==========
        // Bedroom
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'King bed 180cmx200cm',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => '4 Pillows',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => '3 Foam Mattresses',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Sleeping Bag',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => '3 Bedside Lamps',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Sofa with 2 Pillows',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => '3 Coffee Tables',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Comfortable Carpet',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Standing Clothes Rack',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Console Table',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => '4 Power Outlets',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Dispenser + Water Jug',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Smart TV',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Wifi',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // Terrace
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Luxury Dining Table',
            'type' => 'private',
            'icon' => null,
            'description' => 'Terrace',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => '5 Rattan Chairs',
            'type' => 'private',
            'icon' => null,
            'description' => 'Terrace',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => '4 Log Stools',
            'type' => 'private',
            'icon' => null,
            'description' => 'Terrace',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'BBQ Equipment',
            'type' => 'private',
            'icon' => null,
            'description' => 'Terrace',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => '2 Hanging Lamps',
            'type' => 'private',
            'icon' => null,
            'description' => 'Terrace',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Hanging Terrace with Roof',
            'type' => 'private',
            'icon' => null,
            'description' => 'Terrace',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // Pantry
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Pantry Table',
            'type' => 'private',
            'icon' => null,
            'description' => 'Pantry',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Round Washbasin',
            'type' => 'private',
            'icon' => null,
            'description' => 'Pantry',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Refrigerator',
            'type' => 'private',
            'icon' => null,
            'description' => 'Pantry',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Portable Stove',
            'type' => 'private',
            'icon' => null,
            'description' => 'Pantry',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'BBQ Pan',
            'type' => 'private',
            'icon' => null,
            'description' => 'Pantry',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Power Outlet',
            'type' => 'private',
            'icon' => null,
            'description' => 'Pantry',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // Bathroom
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Bathtub',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bathroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Exhaust Fan',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bathroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Cabinet',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bathroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Hot Shower',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bathroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => '5 Toothbrushes + Toothpaste',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bathroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Shampoo',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bathroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Soap',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bathroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // ========== PUBLIC FACILITIES ==========
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Bathroom with Water Heater',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Drinking Water & Dispenser',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Large Shared Table',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'Bonfire + Bonfire Grill',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => '10 Hammocks',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTiluCabin->id,
            'name' => 'BBQ Grill',
            'type' => 'shared',
            'icon' => null,
            'description' => '(does not include charcoal, food ingredients, and other supplies)',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // Assign icons using FacilityIconHelper
        FacilityIconHelper::assignIcons($facilities);

        DB::transaction(function () use ($facilities) {
            foreach ($facilities as $fac) {
                DB::table('facilities')->updateOrInsert(
                    [
                        'area_id' => $fac['area_id'], 
                        'name' => $fac['name'],
                        'description' => $fac['description'] ?? null
                    ],
                    [
                        'type' => $fac['type'] ?? null,
                        'icon' => $fac['icon'] ?? null,
                        'created_at' => $fac['created_at'] ?? now(),
                        'updated_at' => $fac['updated_at'] ?? now(),
                    ]
                );
            }
        });

        $this->command->info('Facilities for Pineus Tilu Cabin seeded successfully!');
    }
}