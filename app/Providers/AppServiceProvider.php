<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('is-admin', fn(User $user) => $user->role == 'admin');
        Gate::define('is-operator', fn(User $user) => $user->role == 'operator');
        Gate::define('is-superadmin', fn(User $user) => $user->role == 'super-admin');

        // Optional: superadmin bisa akses semua
        Gate::before(function (User $user, $ability) {
            return $user->role === 'superadmin' ? true : null;
        });
    }
}
