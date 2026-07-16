<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ActivityLog;
use App\Livewire\Settings\Password as PasswordComponent;
use App\Livewire\Settings\Profile as ProfileComponent;
use App\Livewire\Settings\TwoFactor as TwoFactorComponent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Failed;
use Livewire\Livewire;
use Tests\TestCase;

class SecurityAuditLogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1: Log Registrasi User Baru
     */
    public function test_user_registration_logs_activity(): void
    {
        $this->seed();

        $user = User::factory()->create([
            'email' => 'newuser@example.com'
        ]);

        event(new Registered($user));

        $this->assertDatabaseHas('activity_logs', [
            'event' => 'user_registered',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test 2: Log Login Gagal & Deteksi Brute Force
     */
    public function test_failed_login_and_brute_force_detection(): void
    {
        $this->seed();

        // Trigger 5x login gagal
        for ($i = 0; $i < 5; $i++) {
            event(new Failed('web', null, ['email' => 'target@example.com', 'password' => 'wrongpass']));
        }

        // Harus mencatat login_failed minimal 5 kali
        $this->assertDatabaseHas('activity_logs', [
            'event' => 'login_failed',
        ]);

        // Harus mencatat deteksi brute_force
        $this->assertDatabaseHas('activity_logs', [
            'event' => 'brute_force',
        ]);
    }

    /**
     * Test 3: Log Ganti Password profil
     */
    public function test_manual_password_change_logs_activity(): void
    {
        $this->seed();
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(PasswordComponent::class)
            ->set('current_password', 'password')
            ->set('password', 'NewSecurePassword123!')
            ->set('password_confirmation', 'NewSecurePassword123!')
            ->call('updatePassword');

        $this->assertDatabaseHas('activity_logs', [
            'event' => 'password_changed',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test 4: Log Update Profil (Email & Nama)
     */
    public function test_profile_update_logs_activity(): void
    {
        $this->seed();
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com'
        ]);

        $this->actingAs($user);

        Livewire::test(ProfileComponent::class)
            ->set('name', 'Updated Name')
            ->set('email', 'updated@example.com')
            ->call('updateProfileInformation');

        $this->assertDatabaseHas('activity_logs', [
            'event' => 'profile_updated',
            'user_id' => $user->id,
        ]);
    }
}
