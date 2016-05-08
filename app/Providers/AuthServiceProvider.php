<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define('update-inn', function ($user, $inn) {
            return $user->id === $inn->owner_id || $user->id === '1';
        });

        $gate->define('admin', function ($user) {
            return $user->id === '1';
        });

        $gate->define('view-order', function($user, $order) {
            return $user->id === $order->customer_id;
        });

        $gate->define('update-order', function($user, $order) {
            return $user->id === $order->customer_id;
        });
    }
}
