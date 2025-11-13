<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Gate for Vet role - only verified veterinarians can access vet features
        Gate::define('isVet', function ($user) {
            return isset($user->role) && $user->role === 'vet' && (bool) $user->is_verified_vet;
        });

        // Optional: other roles if you need them elsewhere
        Gate::define('isAdmin', function ($user) {
            return isset($user->role) && $user->role === 'admin';
        });

        Gate::define('isUser', function ($user) {
            return isset($user->role) && $user->role === 'user';
        });
    }
}