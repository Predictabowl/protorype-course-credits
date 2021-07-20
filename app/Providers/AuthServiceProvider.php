<?php

namespace App\Providers;

use App\Models\User;
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
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        //Gate::before(fn($user, $ability) => $user->isAdmin()); //before will disable following checks
        //Gate::define("edit-courses", fn($user) => $user->isSupervisor() || $user->isAdmin());
        //Gate::after(fn($user) => $user->isAdmin());
    }
}
