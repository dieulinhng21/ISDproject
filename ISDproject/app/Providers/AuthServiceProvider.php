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
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin', function ($user) {
            if ($user->role == 1){
                return true;
            }
                return false;
        });
        // Gate::define('create_apartment',function($user){
        //     return $user->role == 1;
        // });
        // Gate::define('edit_apartment',function($user){
        //     return $user->role == 1;
        // });
        // Gate::define('create_contract',function($user){
        //     return $user->role == 1;
        // });
        // Gate::define('edit_contract',function($user){
        //     return $user->role == 1;
        // });
        // Gate::define('create_customer',function($user){
        //     return $user->role == 1;
        // });
        // Gate::define('edit_customer',function($user){
        //     return $user->role == 1;
        // });
        // Gate::define('create_flat',function($user){
        //     return $user->role == 1;
        // });
        // Gate::define('edit_flat',function($user){
        //     return $user->role == 1;
        // });
        // Gate::define('create_manager',function($user){
        //     return $user->role == 1;
        // });
        // Gate::define('edit_manager',function($user){
        //     return $user->role == 1;
        // });
        // Gate::define('create_project',function($user){
        //     return $user->role == 1;
        // });
        // Gate::define('edit_project',function($user){
        //     return $user->role == 1;
        // });

    }
}
