<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call RolePermissionSeeder first to create roles and permissions
        $this->call([
            RolePermissionSeeder::class,
            AreaSeeder::class,
            AreaUnitSeeder::class,
            SeasonDateSeeder::class,
            TentPriceSeeder::class,
            ItemSeeder::class,
            ItemPriceSeeder::class,

            // Outbound data
            OutboundSeeder::class,
            OutboundVariantSeeder::class,
            OutboundVariantPriceSeeder::class,
            OutboundPriceSeeder::class,

            PineusTilu1Seeder::class,
            PineusTilu2Seeder::class,
            PineusTilu3Seeder::class,
            PineusTilu4Seeder::class,
            PineusTiluCabinSeeder::class,
            PineusTiluCabinVVIPSeeder::class,
            
            // Gallery data
            GallerySeeder::class,

            // FAQ data
            FaqSeeder::class,
        ]);

        // User::factory(10)->create();

        // Create test user with user role (use firstOrCreate to avoid duplicate)
        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );
        
        if (!$testUser->hasRole('user')) {
            $testUser->assignRole('user');
        }
    }
}
