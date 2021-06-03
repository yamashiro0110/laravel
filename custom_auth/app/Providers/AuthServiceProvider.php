<?php

namespace App\Providers;

use App\Auth\TokenUserGuard;
use App\Auth\TokenUserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

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

        // driver(guard)
        Auth::extend('sample_token', function($app, $name, array $config) {
            Log::debug("TokenUserGuard 初期化", ['name', $name, 'config', $config]);

            $session = $app['session.store'];
            $guard = new TokenUserGuard($name, $session);

            if (method_exists($guard, 'setCookieJar')) {
                $guard->setCookieJar($app['cookie']);
            }

            if (method_exists($guard, 'setDispatcher')) {
                $guard->setDispatcher($app['events']);
            }

            if (method_exists($guard, 'setRequest')) {
                $guard->setRequest($app->refresh('request', $guard, 'setRequest'));
            }

            return $guard;
        });

        // user provider
        Auth::provider('sample_token_user', function($app, array $config) {
            return new TokenUserProvider();
        });

    }
}
