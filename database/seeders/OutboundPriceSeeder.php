<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OutboundPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Look up Outbound IDs by slug (assumes outboundSeeder ran first)
        $arungId = DB::table('outbounds')->where('slug', 'arung-jeram')->value('id');
        $flyingId = DB::table('outbounds')->where('slug', 'flying-fox')->value('id');
        $offroadId = DB::table('outbounds')->where('slug', 'offroad')->value('id');
        $paintballId = DB::table('outbounds')->where('slug', 'paintball')->value('id');
        $teamId = DB::table('outbounds')->where('slug', 'team-building')->value('id');

        $rows = [];

        // Activities without variants: store as outbound_id
        if ($flyingId) {
            $rows[] = [
                'outbound_id' => $flyingId,
                'price' => 35000,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($offroadId) {
            $rows[] = [
                'outbound_id' => $offroadId,
                'price' => 1500000,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($paintballId) {
            $rows[] = [
                'outbound_id' => $paintballId,
                'price' => 80000,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($teamId) {
            $rows[] = [
                'outbound_id' => $teamId,
                'price' => 175000,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Documentation add-on for Arung Jeram: stored as a price row linked to outbound_id (variant null).
        // Alternative: create an outbound_variant for addon; here we add as a separate price row for simplicity.
        if ($arungId) {
            $rows[] = [
                'outbound_id' => $arungId,
                'price' => 100000, // dokumentasi tambahan per perahu
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Global transportation price (outbound_id = NULL) to represent per-mobil transport fee
        $rows[] = [
            'outbound_id' => null,
            'price' => 200000, // Rp 200.000 per mobil
            'created_at' => $now,
            'updated_at' => $now,
        ];

        if (!empty($rows)) {
            DB::table('prices')->insert($rows);
        }
    }
}