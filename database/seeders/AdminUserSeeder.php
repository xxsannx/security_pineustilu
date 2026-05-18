<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure roles exist
        Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // Create Superadmin user
        $superAdmin = User::updateOrCreate(
            ['email' => 'pineust@gmail.com'],
            [
                'name' => 'Pineus Tilu Admin',
                'password' => bcrypt('PineusTiluAdmin2025.'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->syncRoles(['super-admin']);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: pineust@gmail.com');
        $this->command->info('Role: super-admin');
    }
}
