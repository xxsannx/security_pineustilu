<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Item;

class ItemPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define prices mapping: item name => price (according to screenshot)
        $itemPrices = [
            // AMENITIES
            'Amenities' => 100000,
            'Amenities VIP' => 150000,
            
            // BREAKFAST & TOOLS
            'Extra Breakfast' => 40000,
            'Bathroom Tools Set' => 25000,
            'Portable Stove Set' => 100000,
            'Cutlery' => 15000,
            
            // BBQ FUEL
            'Charcoal' => 50000,
            'Campfire Woods' => 50000,
            
            // BEEF PRODUCTS
            'Beef Sirloin' => 98000,
            'Beef Slice Short Plate' => 79000,
            'Beef Slice Low Fat' => 85000,
            'Sosis Cocktail Original' => 29000,
            'Sosis Beef Frank Original' => 29000,
            'Super Meatball' => 35000,
            
            // SAUCES
            'BBQ Sauce' => 39000,
            'Bulgogi Sauce' => 39000,
            'Gochujang Sauce' => 39000,
            'Blackpepper Teriyaki Sauce' => 29000,
            'Garlic Teriyaki Sauce' => 29000,
            'Korean BBQ Sauce' => 55000,
        ];

        // Get all items in one query
        $items = Item::whereIn('name', array_keys($itemPrices))->get()->keyBy('name');

        $now = Carbon::now();

        DB::transaction(function () use ($itemPrices, $items, $now) {
            $inserted = 0;
            foreach ($itemPrices as $itemName => $price) {
                if (!isset($items[$itemName])) continue;
                $itemId = $items[$itemName]->id;
                
                // Update the price column on items table directly
                DB::table('items')
                    ->where('id', $itemId)
                    ->update(['price' => $price, 'updated_at' => $now]);
                
                // Skip if a price row for this item (no season) already exists
                $exists = DB::table('prices')
                    ->where('item_id', $itemId)
                    ->whereNull('season_id')
                    ->exists();
                if ($exists) continue;
                DB::table('prices')->insert([
                    'item_id' => $itemId,
                    'unit_id' => null,
                    'outbound_id' => null,
                    'season_id' => null,
                    'price' => $price,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $inserted++;
            }
            $this->command->info('Item prices seeded successfully! New: ' . $inserted);
        });
    }
}