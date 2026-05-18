<?php

namespace Database\Seeders;

use App\Helpers\FacilityIconHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Area;

class PineusTiluCabinVVIPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Pineus Tilu Cabin VVIP area
        $cabinVVIP = Area::where('slug', 'pineus-tilu-cabin-vvip')->first();

        if (!$cabinVVIP) {
            $this->command->error('Area Pineus Tilu Cabin VVIP not found. Please run AreaSeeder first.');
            return;
        }

        $facilities = [];

        // ========== VVIP PRIVATE FACILITIES ==========
        // Bedroom
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => '2 bedrooms with king size beds',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => '4 Pillows each bedroom',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => '2 Storage Box',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => '4 Foam Mattresses',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Table Lamp',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Wifi with Special Router',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Standing Clothes Rack',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Console Table with 2 Smart TV 55 inch',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Comfortable Carpet',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bedroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // Dining & Living Room
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Solid Wood Dining Table for 8 people',
            'type' => 'private',
            'icon' => null,
            'description' => 'Dining & Living Room',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => '2 Benches',
            'type' => 'private',
            'icon' => null,
            'description' => 'Dining & Living Room',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Sofa Sofabed',
            'type' => 'private',
            'icon' => null,
            'description' => 'Dining & Living Room',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Coffee Table',
            'type' => 'private',
            'icon' => null,
            'description' => 'Dining & Living Room',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Shoe Rack',
            'type' => 'private',
            'icon' => null,
            'description' => 'Dining & Living Room',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Welcome Drink',
            'type' => 'private',
            'icon' => null,
            'description' => 'Dining & Living Room',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Trash Bin',
            'type' => 'private',
            'icon' => null,
            'description' => 'Dining & Living Room',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // Kitchen
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Complete Kitchen Equipment',
            'type' => 'private',
            'icon' => null,
            'description' => 'Kitchen',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Kitchen Island',
            'type' => 'private',
            'icon' => null,
            'description' => 'Kitchen',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Refrigerator',
            'type' => 'private',
            'icon' => null,
            'description' => 'Kitchen',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Cooler',
            'type' => 'private',
            'icon' => null,
            'description' => 'Kitchen',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Dispenser',
            'type' => 'private',
            'icon' => null,
            'description' => 'Kitchen',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Water Gallon',
            'type' => 'private',
            'icon' => null,
            'description' => 'Kitchen',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'BBQ Equipment',
            'type' => 'private',
            'icon' => null,
            'description' => 'Kitchen',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Dining Utensils',
            'type' => 'private',
            'icon' => null,
            'description' => 'Kitchen',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // Bathroom
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Private Toilet with Free-standing Bathtub',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bathroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Washbasin',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bathroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Mirror',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bathroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => '8 Pax all extras',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bathroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => '8 Shampoo',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bathroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => '8 Soap',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bathroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Sitting Toilet',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bathroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => '8 Towels',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bathroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // Additional
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => '2 Hammock',
            'type' => 'private',
            'icon' => null,
            'description' => 'Additional',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => '12 meter Bar Table',
            'type' => 'private',
            'icon' => null,
            'description' => 'Additional',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => '6 Benches',
            'type' => 'private',
            'icon' => null,
            'description' => 'Additional',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => '2 Paintings',
            'type' => 'private',
            'icon' => null,
            'description' => 'Additional',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // ========== VVIP PUBLIC FACILITIES ==========
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Bathroom with Water Heater',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => '3 Bonfires + Bonfire Grill',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Drinking Water & Dispenser',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => '4 Shared Dining Tables',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => '13 Hammock',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => '28 Coffee Tables',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
            'name' => 'Wifi',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $cabinVVIP->id,
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

        $this->command->info('Facilities for Pineus Tilu Cabin VVIP seeded successfully!');
    }
}
