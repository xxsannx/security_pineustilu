<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth page
     */
    public function redirectToGoogle()
    {
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirectUri = config('services.google.redirect');

        if (blank($clientId) || blank($clientSecret) || blank($redirectUri)) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Google login belum dikonfigurasi. Pastikan GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, dan GOOGLE_REDIRECT_URI sudah diisi di .env, lalu jalankan php artisan config:clear.'
                );
        }

        // Use stateless to avoid session state mismatch issues
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Handle Google callback
     */
    public function handleGoogleCallback()
    {
        try {
            $clientId = config('services.google.client_id');
            $clientSecret = config('services.google.client_secret');
            $redirectUri = config('services.google.redirect');

            if (blank($clientId) || blank($clientSecret) || blank($redirectUri)) {
                return redirect()
                    ->route('login')
                    ->with(
                        'error',
                        'Google login belum dikonfigurasi. Hubungi admin untuk mengisi GOOGLE_CLIENT_ID/SECRET/REDIRECT di .env.'
                    );
            }

            // Get user info from Google (stateless to avoid InvalidStateException)
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Check if user already exists
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($user) {
                // Update Google ID if not set
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                    ]);
                }
                
                // Ensure user has a role (for existing users without role)
                if ($user->roles->isEmpty()) {
                    $user->assignRole('user');
                }
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(uniqid()), // Random password
                    'email_verified_at' => now(), // Auto verify email
                ]);
                
                // Assign default role to new user
                $user->assignRole('user');
            }
            
            // Login user
            Auth::login($user);
            
            // Redirect to intended page or dashboard
            return redirect()->intended('/dashboard')->with('success', 'Successfully logged in with Google!');
            
        } catch (\Exception $e) {
            report($e);

            return redirect()
                ->route('login')
                ->with('error', 'Failed to login with Google. Please try again.');
        }
    }
}