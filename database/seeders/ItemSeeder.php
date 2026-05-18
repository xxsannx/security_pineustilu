<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            // ========================================
            // AMENITIES
            // ========================================
            [
                'name' => 'Amenities',
                'description' => 'Foam mattress, sleeping bag, breakfast',
                'type' => 'pax', // per person
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Amenities VIP',
                'description' => 'Foam mattress, sleeping bag, breakfast, and toiletries',
                'type' => 'pax', // per person
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // ========================================
            // BREAKFAST & TOOLS
            // ========================================
            [
                'name' => 'Extra Breakfast',
                'description' => 'Buffet - all you can eat',
                'type' => 'pax', // per person
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Bathroom Tools Set',
                'description' => '1 Towels, 1 Toothbrush + Toothpaste',
                'type' => 'set', // per set
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Portable Stove Set',
                'description' => 'Includes 1 stove, 1 gas cylinder, 1 frying pan, 1 pot, and 1 tong',
                'type' => 'set', // per set
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Cutlery',
                'description' => 'Includes 1 plate with rice paper, 1 plastic spoon & fork, 1 chopsticks, 1 paper cup, and 1 paper bowl',
                'type' => 'set', // per set
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // ========================================
            // BBQ FUEL
            // ========================================
            [
                'name' => 'Charcoal',
                'description' => null,
                'type' => 'bag', // per bag
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Campfire Woods',
                'description' => null,
                'type' => 'bundle', // per bundle
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // ========================================
            // BEEF PRODUCTS
            // ========================================
            [
                'name' => 'Beef Sirloin',
                'description' => '4pcs/500g',
                'type' => 'pack', // per pack
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Beef Slice Short Plate',
                'description' => '500g',
                'type' => 'pack', // per pack
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Beef Slice Low Fat',
                'description' => '500g',
                'type' => 'pack', // per pack
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Sosis Cocktail Original',
                'description' => 'small size 500g',
                'type' => 'pack', // per pack
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Sosis Beef Frank Original',
                'description' => 'medium size 500g',
                'type' => 'pack', // per pack
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Super Meatball',
                'description' => '35pcs - 38pcs/500g',
                'type' => 'pack', // per pack
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // ========================================
            // SAUCES
            // ========================================
            [
                'name' => 'BBQ Sauce',
                'description' => '1 bottle 300ml',
                'type' => 'bottle', // per bottle
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Bulgogi Sauce',
                'description' => '1 bottle 300ml',
                'type' => 'bottle', // per bottle
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Gochujang Sauce',
                'description' => '1 bottle 300ml',
                'type' => 'bottle', // per bottle
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Blackpepper Teriyaki Sauce',
                'description' => '1 bottle 300ml',
                'type' => 'bottle', // per bottle
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Garlic Teriyaki Sauce',
                'description' => '1 bottle 300ml',
                'type' => 'bottle', // per bottle
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Korean BBQ Sauce',
                'description' => '1 bottle 500ml',
                'type' => 'bottle', // per bottle
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Upsert items by name to be idempotent
        foreach ($items as $it) {
            DB::table('items')->updateOrInsert(
                ['name' => $it['name']],
                [
                    'description' => $it['description'] ?? null,
                    'type' => $it['type'] ?? null,
                    'updated_at' => Carbon::now(),
                ]
            );
        }
    }
}