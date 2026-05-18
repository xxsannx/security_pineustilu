<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            [
                'name' => 'Pineus Tilu 1',
                'description' => 'Immersive experience with the soothing sound of river and the beautiful scenery of pine forest.',
                'slug' => 'pineus-tilu-1',
                'extra_charge_full' => 100000,
                'extra_charge_breakfast' => 40000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Pineus Tilu 2',
                'slug' => 'pineus-tilu-2',
                'description' => 'Ideal for families, Pineus Tilu 2 is equipped with a children\'s play pool, creating a fun and safe area for kids to play while parents can relax and enjoy nature.',
                'extra_charge_full' => 100000,
                'extra_charge_breakfast' => 40000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Pineus Tilu 3 VIP',
                'slug' => 'pineus-tilu-3-vip',
                'description' => 'Enjoy exclusive comfort at Pineus Tilu 3 VIP, with more complete facilities for a premium glamping experience surrounded by the cool pine forest.',
                'extra_charge_full' => 150000,
                'extra_charge_breakfast' => 40000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Pineus Tilu 4',
                'slug' => 'pineus-tilu-4',
                'description' => 'Pineus Tilu 4 offers more tents and a larger area, making it the perfect choice for groups or communities who want to camp together in a lively atmosphere.',
                'extra_charge_full' => 100000,
                'extra_charge_breakfast' => 40000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Pineus Tilu Cabin',
                'slug' => 'pineus-tilu-cabin',
                'description' => 'A more private and comfortable stay experience in exclusive Pineus Tilu cabins, perfect for couples or small families who desire more privacy.',
                'extra_charge_full' => 0,
                'extra_charge_breakfast' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Pineus Tilu Cabin VVIP',
                'slug' => 'pineus-tilu-cabin-vvip',
                'description' => 'Private exclusive cabin with premium facilities & larger area, provides not only comforts but also warm atmosphere. ',
                'extra_charge_full' => 0,
                'extra_charge_breakfast' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Upsert by slug to be idempotent
        foreach ($areas as $a) {
            DB::table('areas')->updateOrInsert(
                ['slug' => $a['slug']],
                [
                    'name' => $a['name'],
                    'description' => $a['description'] ?? null,
                    'extra_charge_full' => $a['extra_charge_full'] ?? 0,
                    'extra_charge_breakfast' => $a['extra_charge_breakfast'] ?? 0,
                    'updated_at' => Carbon::now(),
                ]
            );
        }
    }
}