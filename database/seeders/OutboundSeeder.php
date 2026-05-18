<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OutboundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $outbounds = [
            [
                'name' => 'Rafting',
                'slug' => 'arung-jeram',
                'description' => 'Guide & rescue team, first aid kit/safety equipment, rinse area, insurance, local transportation & instructor, documentation (photos and videos). Duration 5 Km (~90 minutes).',
                'pricing_type' => 'per_unit',
                'unit_name' => 'boat',
                'min_participants' => 1, // min booking unit (boat)
                'max_participants' => null, // total participants not strictly limited here
                'min_age' => 10,
                'duration' => 90,
                'distance' => 5.00,
                'has_variants' => true,
                'allows_documentation_addon' => true,
                'requires_transportation' => false, // special handling: no general pickup fee here
                'has_camping_package' => true,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Flying Fox',
                'slug' => 'flying-fox',
                'description' => 'Safety harness, includes instructor, ticket, first aid kit/safety equipment. Length 200 meters with 12 meters height.',
                'pricing_type' => 'per_pax',
                'unit_name' => null,
                'min_participants' => 4,
                'max_participants' => null,
                'min_age' => 6,
                'duration' => 30,
                'distance' => 0.20,
                'has_variants' => false,
                'allows_documentation_addon' => false,
                'requires_transportation' => true,
                'has_camping_package' => false,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Offroad',
                'slug' => 'offroad',
                'description' => 'Land Rover offroad unit, includes driver, instructor, first aid kit/safety equipment, insurance, local transportation and ticket. Duration 9 Km (~120 minutes).',
                'pricing_type' => 'per_unit',
                'unit_name' => 'car',
                'min_participants' => 1,
                'max_participants' => 7, // per unit
                'min_age' => 4,
                'duration' => 120,
                'distance' => 9.00,
                'has_variants' => false,
                'allows_documentation_addon' => false,
                'requires_transportation' => false, // Offroad excluded from general pickup fee
                'has_camping_package' => false,
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Fun ATV',
                'slug' => 'fun-atv',
                'description' => 'ATV unit, helmet, instructor, first aid kit/safety equipment, insurance & ticket. Duration 4 Km (60 minutes).',
                'pricing_type' => 'per_pax',
                'unit_name' => 'atv',
                'min_participants' => 1,
                'max_participants' => 2, // per ATV
                'min_age' => 5,
                'duration' => 60,
                'distance' => 4.00,
                'has_variants' => true, // single/double
                'allows_documentation_addon' => false,
                'requires_transportation' => true,
                'has_camping_package' => false,
                'is_active' => true,
                'sort_order' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Paintball',
                'slug' => 'paintball',
                'description' => 'Uniform, protective vest, mask/goggles, paintball marker/gun, 30 bullets, includes instructor, ticket, first aid kit/safety equipment.',
                'pricing_type' => 'per_pax',
                'unit_name' => null,
                'min_participants' => 10,
                'max_participants' => 70,
                'min_age' => 13,
                'duration' => 120,
                'distance' => null,
                'has_variants' => false,
                'allows_documentation_addon' => false,
                'requires_transportation' => true,
                'has_camping_package' => false,
                'is_active' => true,
                'sort_order' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Team Building',
                'slug' => 'team-building',
                'description' => 'Equipment, includes instructor, sound system & first aid kit/safety equipment.',
                'pricing_type' => 'per_pax',
                'unit_name' => null,
                'min_participants' => 5,
                'max_participants' => 100,
                'min_age' => 11,
                'duration' => 120,
                'distance' => null,
                'has_variants' => false,
                'allows_documentation_addon' => false,
                'requires_transportation' => true,
                'has_camping_package' => false,
                'is_active' => true,
                'sort_order' => 6,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Use upsert to handle duplicates - update if slug exists, insert if not
        DB::table('outbounds')->upsert(
            $outbounds,
            ['slug'], // unique key to check for duplicates
            [ // columns to update if duplicate found
                'name',
                'description',
                'pricing_type',
                'unit_name',
                'min_participants',
                'max_participants',
                'min_age',
                'duration',
                'distance',
                'has_variants',
                'allows_documentation_addon',
                'requires_transportation',
                'has_camping_package',
                'is_active',
                'sort_order',
                'updated_at',
            ]
        );
    }
}