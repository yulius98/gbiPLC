<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use App\Auth\Guards\JWTCookieGuard;

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
        // Configure default password validation rules
        Password::defaults(function () {
            $rule = Password::min(8);

            return app()->isProduction()
                        ? $rule->mixedCase()->numbers()->symbols()->uncompromised()
                        : $rule;
        });

        // Register custom JWT Cookie guard
        Auth::extend('jwt-cookie', function ($app, $name, array $config) {
            return new JWTCookieGuard(
                Auth::createUserProvider($config['provider']),
                $app['request']
            );
        });
    }
}
