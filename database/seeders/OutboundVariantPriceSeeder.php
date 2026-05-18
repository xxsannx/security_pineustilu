<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OutboundVariantPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Look up variant IDs by label (assumes outboundVariantSeeder ran first)
        $v_ars_lt4 = DB::table('outbound_variants')->where('variant_label', '1 Boat < 4 people')->value('id');
        $v_ars_4 = DB::table('outbound_variants')->where('variant_label', '1 Boat 4 people')->value('id');
        $v_ars_5 = DB::table('outbound_variants')->where('variant_label', '1 Boat 5 people')->value('id');
        $v_ars_6 = DB::table('outbound_variants')->where('variant_label', '1 Boat 6 people')->value('id');

        $v_atv_single = DB::table('outbound_variants')->where('variant_label', 'Single (1 pax)')->value('id');
        $v_atv_double = DB::table('outbound_variants')->where('variant_label', 'Double (2 pax)')->value('id');

        // Helper function to upsert price
        $upsertPrice = function ($variantId, $price) use ($now) {
            if (!$variantId) return;
            
            DB::table('prices')->updateOrInsert(
                ['outbound_variant_id' => $variantId, 'season_id' => null],
                [
                    'outbound_id' => null,
                    'unit_id' => null,
                    'item_id' => null,
                    'price' => $price,
                    'updated_at' => $now,
                ]
            );
        };

        // Rafting variants
        $upsertPrice($v_ars_lt4, 550000);
        $upsertPrice($v_ars_4, 700000);
        $upsertPrice($v_ars_5, 850000);
        $upsertPrice($v_ars_6, 950000);

        // ATV variants
        $upsertPrice($v_atv_single, 175000);
        $upsertPrice($v_atv_double, 250000);
    }
}