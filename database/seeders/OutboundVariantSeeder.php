<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OutboundVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Find Outbound IDs by slug to avoid hardcoding IDs (assumes outboundSeeder ran first)
        $arungId = DB::table('outbounds')->where('slug', 'arung-jeram')->value('id');
        $atvId = DB::table('outbounds')->where('slug', 'fun-atv')->value('id');

        $variants = [];

        if ($arungId) {
            // Arung Jeram variants (capacity-based)
            $variants[] = [
                'outbound_id' => $arungId,
                'variant_type' => 'capacity_based',
                'variant_label' => '1 Boat < 4 people',
                'min_pax_per_unit' => 1,
                'max_pax_per_unit' => 3,
                'includes_documentation' => false,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $variants[] = [
                'outbound_id' => $arungId,
                'variant_type' => 'capacity_based',
                'variant_label' => '1 Boat 4 people',
                'min_pax_per_unit' => 4,
                'max_pax_per_unit' => 4,
                'includes_documentation' => true,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $variants[] = [
                'outbound_id' => $arungId,
                'variant_type' => 'capacity_based',
                'variant_label' => '1 Boat 5 people',
                'min_pax_per_unit' => 5,
                'max_pax_per_unit' => 5,
                'includes_documentation' => true,
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $variants[] = [
                'outbound_id' => $arungId,
                'variant_type' => 'capacity_based',
                'variant_label' => '1 Boat 6 people',
                'min_pax_per_unit' => 6,
                'max_pax_per_unit' => 6,
                'includes_documentation' => true,
                'is_active' => true,
                'sort_order' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($atvId) {
            // Fun ATV variants (single/double)
            $variants[] = [
                'outbound_id' => $atvId,
                'variant_type' => 'single',
                'variant_label' => 'Single (1 pax)',
                'min_pax_per_unit' => 1,
                'max_pax_per_unit' => 1,
                'includes_documentation' => false,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $variants[] = [
                'outbound_id' => $atvId,
                'variant_type' => 'double',
                'variant_label' => 'Double (2 pax)',
                'min_pax_per_unit' => 2,
                'max_pax_per_unit' => 2,
                'includes_documentation' => false,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($variants)) {
            DB::table('outbound_variants')->insert($variants);
        }
    }
}