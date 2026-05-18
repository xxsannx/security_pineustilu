<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Area;
use App\Helpers\FacilityIconHelper;

class PineusTilu3Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Pineus Tilu 3 VIP area
        $pineusTilu3 = Area::where('name', 'Pineus Tilu 3 VIP')->first();

        if (!$pineusTilu3) {
            $this->command->error('Area Pineus Tilu 3 VIP not found. Please run AreaSeeder first.');
            return;
        }

        $facilities = [];

        // ========== PRIVATE FACILITIES ==========
        // 6-person facilities for Tent 6.3 (Deck 1, 2, 3, 4, 5)
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => '6 Foam Mattresses',
            'type' => 'private',
            'icon' => null,
            'description' => '6-person facilities for Tent 6.3 (Deck 1, 2, 3, 4, 5) includes',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => '6 Pillows',
            'type' => 'private',
            'icon' => null,
            'description' => '6-person facilities for Tent 6.3 (Deck 1, 2, 3, 4, 5) includes',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => '6 Towels',
            'type' => 'private',
            'icon' => null,
            'description' => '6-person facilities for Tent 6.3 (Deck 1, 2, 3, 4, 5) includes',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => '6 Breakfasts',
            'type' => 'private',
            'icon' => null,
            'description' => '6-person facilities for Tent 6.3 (Deck 1, 2, 3, 4, 5) includes',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => '6 Sleeping Bags',
            'type' => 'private',
            'icon' => null,
            'description' => '6-person facilities for Tent 6.3 (Deck 1, 2, 3, 4, 5) includes',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => '6 Toothbrushes with Toothpaste',
            'type' => 'private',
            'icon' => null,
            'description' => '6-person facilities for Tent 6.3 (Deck 1, 2, 3, 4, 5) includes',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // 5-person facilities for Tent 5.2 (Deck 6, 7, 8, 9)
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => '5 Foam Mattresses',
            'type' => 'private',
            'icon' => null,
            'description' => '5-person facilities for Tent 5.2 (Deck 6, 7, 8, 9) includes',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => '5 Pillows',
            'type' => 'private',
            'icon' => null,
            'description' => '5-person facilities for Tent 5.2 (Deck 6, 7, 8, 9) includes',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => '5 Towels',
            'type' => 'private',
            'icon' => null,
            'description' => '5-person facilities for Tent 5.2 (Deck 6, 7, 8, 9) includes',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => '5 Breakfasts',
            'type' => 'private',
            'icon' => null,
            'description' => '5-person facilities for Tent 5.2 (Deck 6, 7, 8, 9) includes',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => '5 Sleeping Bags',
            'type' => 'private',
            'icon' => null,
            'description' => '5-person facilities for Tent 5.2 (Deck 6, 7, 8, 9) includes',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => '5 Toothbrushes with Toothpaste',
            'type' => 'private',
            'icon' => null,
            'description' => '5-person facilities for Tent 5.2 (Deck 6, 7, 8, 9) includes',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // Deck Terrace
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => '2 Hammock Swings',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck Terrace',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Net Hammock',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck Terrace',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => '6 Rattan Chairs',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck Terrace',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Refrigerator',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck Terrace',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => '5 Power Outlets',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck Terrace',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Swing Chair',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck Terrace',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Lamp',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck Terrace',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Trash Bin',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck Terrace',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Drinking Water & Dispenser',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck Terrace',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Pantry with table and sink',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck Terrace',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Long Solid Wood Luxury Table (80-85 cm x 2.5 m)',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck Terrace',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'BBQ Grill',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck Terrace',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // Deck & Tent
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Family Tent 6.3',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck & Tent',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Family Tent 5.2',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck & Tent',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => '5 Floor Cushion Seats',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck & Tent',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => '2 Beanbags',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck & Tent',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Coffee Table',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck & Tent',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Standing Clothes Rack',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck & Tent',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => '2 Table Lamps',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck & Tent',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => '1 Power Outlet',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck & Tent',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Indoor and Outdoor Mat',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck & Tent',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Trash Bin',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck & Tent',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => '5 Wooden Chairs',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck & Tent',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Carpet',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck & Tent',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Bar Table',
            'type' => 'private',
            'icon' => null,
            'description' => 'Deck & Tent',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // Bathroom
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Private Bathroom',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bathroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Mirror',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bathroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Shampoo',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bathroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Soap',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bathroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Closet',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bathroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Washbasin',
            'type' => 'private',
            'icon' => null,
            'description' => 'Bathroom',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // ========== PUBLIC FACILITIES ==========
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Bench',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Trash Bin',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Wooden Chair',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Outdoor Lamp',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Bonfire',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => '5m Long Table',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Wooden Statue',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
            'name' => 'Wifi',
            'type' => 'shared',
            'icon' => null,
            'description' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        $facilities[] = [
            'area_id' => $pineusTilu3->id,
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
                // Use area_id + name + description as unique constraint to allow duplicate names in different groups
                $matchConditions = [
                    'area_id' => $fac['area_id'], 
                    'name' => $fac['name']
                ];
                
                // Include description in match if it exists
                if (!empty($fac['description'])) {
                    $matchConditions['description'] = $fac['description'];
                } else {
                    // For null descriptions, match explicitly on null
                    $existing = DB::table('facilities')
                        ->where('area_id', $fac['area_id'])
                        ->where('name', $fac['name'])
                        ->whereNull('description')
                        ->first();
                    
                    if ($existing) {
                        DB::table('facilities')
                            ->where('id', $existing->id)
                            ->update([
                                'type' => $fac['type'] ?? null,
                                'icon' => $fac['icon'] ?? null,
                                'updated_at' => $fac['updated_at'] ?? now(),
                            ]);
                        continue;
                    } else {
                        DB::table('facilities')->insert([
                            'area_id' => $fac['area_id'],
                            'name' => $fac['name'],
                            'type' => $fac['type'] ?? null,
                            'icon' => $fac['icon'] ?? null,
                            'description' => null,
                            'created_at' => $fac['created_at'] ?? now(),
                            'updated_at' => $fac['updated_at'] ?? now(),
                        ]);
                        continue;
                    }
                }
                
                DB::table('facilities')->updateOrInsert(
                    $matchConditions,
                    [
                        'type' => $fac['type'] ?? null,
                        'icon' => $fac['icon'] ?? null,
                        'updated_at' => $fac['updated_at'] ?? now(),
                    ]
                );
            }
        });

        $this->command->info('Facilities for Pineus Tilu 3 seeded successfully!');
    }
}