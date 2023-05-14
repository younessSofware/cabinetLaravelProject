<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RolesAndPermissionsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Get the user and role instances
        $user = User::find(7);
        $role = Role::findByName('admin');

        // Assign the role to the user
        $user->assignRole($role);

    }
}
