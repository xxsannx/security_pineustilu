<?php

use Illuminate\Support\Facades\Auth;
use App\Models\User;

if (!function_exists('can_access')) {
    /**
     * Check if the authenticated user has permission
     *
     * @param string $permission
     * @return bool
     */
    function can_access(string $permission): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        return Auth::check() && $user && $user->can($permission);
    }
}

if (!function_exists('has_role')) {
    /**
     * Check if the authenticated user has role
     *
     * @param string|array $roles
     * @return bool
     */
    function has_role(string|array $roles): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        return Auth::check() && $user && $user->hasRole($roles);
    }
}

if (!function_exists('is_super_admin')) {
    /**
     * Check if the authenticated user is super admin
     *
     * @return bool
     */
    function is_super_admin(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        return Auth::check() && $user && $user->hasRole('super-admin');
    }
}

if (!function_exists('is_admin')) {
    /**
     * Check if the authenticated user is admin or super admin
     *
     * @return bool
     */
    function is_admin(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        return Auth::check() && $user && $user->hasAnyRole(['super-admin', 'admin']);
    }
}

if (!function_exists('is_staff')) {
    /**
     * Check if the authenticated user is staff
     *
     * @return bool
     */
    function is_staff(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        return Auth::check() && $user && $user->hasRole('staff');
    }
}

if (!function_exists('is_customer')) {
    /**
     * Check if the authenticated user is a regular user (customer)
     *
     * @return bool
     */
    function is_customer(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        return Auth::check() && $user && $user->hasRole('user');
    }
}

if (!function_exists('user_roles')) {
    /**
     * Get user roles as string
     *
     * @return string
     */
    function user_roles(): string
    {
        if (!Auth::check()) {
            return '';
        }
        
        /** @var User $user */
        $user = Auth::user();
        return $user->getRoleNames()->implode(', ');
    }
}
