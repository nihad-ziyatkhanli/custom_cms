<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\MenuItem' => 'App\Policies\MenuItemPolicy',
        'App\Role' => 'App\Policies\RolePolicy',
        'App\User' => 'App\Policies\UserPolicy',
        'App\File' => 'App\Policies\FilePolicy',
        'App\Post' => 'App\Policies\PostPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        /* Me: Gates here. */
        Gate::define('admin', function ($user) {
            return session('menu_data') !== null && session('menu_data')->isNotEmpty();
        });

        /* Me: Loop through the permissions and add them to the gates. */
        //$permissions = []; // Me: Use this line when migrating tables, and comment the next one.
        $permissions = \App\Permission::browse();
        foreach ($permissions as $p) {
            Gate::define($p->code, function ($user) use($p) {
                return $user->role->rmps->filter(function($rmp) use($p) {
                    return $rmp->permission->code === $p->code;
                })->isNotEmpty();
            });
        }
    }
}
