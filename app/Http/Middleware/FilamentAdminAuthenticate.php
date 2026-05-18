<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FilamentAdminAuthenticate extends Middleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $guard = $guards[0] ?? null;

        // Check if user is authenticated
        if (!Auth::guard($guard)->check()) {
            // Redirect to existing login page with intended URL
            return redirect()->guest(route('login'));
        }

        $user = Auth::guard($guard)->user();

        // Check if user can access the Filament panel
        if ($user instanceof FilamentUser) {
            if (! $user->canAccessPanel(Filament::getCurrentPanel())) {
                abort(403, 'You do not have access to the admin panel.');
            }
        }

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}
