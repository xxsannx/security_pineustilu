<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignDefaultRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:assign-default-roles {--cleanup : Also cleanup unused roles}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign default "user" role to all users without proper role and cleanup unused roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Valid roles in the system
        $validRoles = ['super-admin', 'admin', 'user'];
        
        // Migrate users with invalid roles (customer, staff, guest) to 'user' role
        $this->info('Checking users with invalid roles...');
        
        $usersToMigrate = User::whereHas('roles', function ($query) use ($validRoles) {
            $query->whereNotIn('name', $validRoles);
        })->get();
        
        foreach ($usersToMigrate as $user) {
            $oldRoles = $user->roles->pluck('name')->toArray();
            $user->syncRoles(['user']);
            $this->line("Migrated user {$user->email} from [" . implode(', ', $oldRoles) . "] to [user]");
        }
        
        if ($usersToMigrate->isEmpty()) {
            $this->info('No users with invalid roles found.');
        }
        
        // Assign 'user' role to users without any role
        $usersWithoutRoles = User::doesntHave('roles')->get();
        
        if ($usersWithoutRoles->isNotEmpty()) {
            $this->info("Found {$usersWithoutRoles->count()} users without roles.");
            
            foreach ($usersWithoutRoles as $user) {
                $user->assignRole('user');
                $this->line("Assigned 'user' role to: {$user->email}");
            }
        } else {
            $this->info('All users have roles assigned.');
        }
        
        // Cleanup unused roles if --cleanup flag is passed
        if ($this->option('cleanup')) {
            $this->info('Cleaning up unused roles...');
            
            $rolesToDelete = Role::whereNotIn('name', $validRoles)->get();
            
            foreach ($rolesToDelete as $role) {
                $this->line("Deleting role: {$role->name}");
                $role->delete();
            }
            
            if ($rolesToDelete->isEmpty()) {
                $this->info('No unused roles to delete.');
            } else {
                $this->info("Deleted {$rolesToDelete->count()} unused roles.");
            }
        }
        
        $this->info('Done!');
        return 0;
    }
}
