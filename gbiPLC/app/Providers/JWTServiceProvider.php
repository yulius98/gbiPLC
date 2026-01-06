<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTServiceProvider extends ServiceProvider
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
        // Pastikan JWT configuration menggunakan tipe data yang benar
        $this->app->afterResolving('config', function ($config) {
            // Pastikan TTL adalah integer
            $ttl = $config->get('jwt.ttl', 60);
            if (is_string($ttl)) {
                $config->set('jwt.ttl', (int) $ttl);
            }

            // Pastikan refresh TTL adalah integer
            $refreshTtl = $config->get('jwt.refresh_ttl', 20160);
            if (is_string($refreshTtl)) {
                $config->set('jwt.refresh_ttl', (int) $refreshTtl);
            }

            // Pastikan leeway adalah integer
            $leeway = $config->get('jwt.leeway', 0);
            if (is_string($leeway)) {
                $config->set('jwt.leeway', (int) $leeway);
            }

            // Pastikan blacklist grace period adalah integer
            $gracePeriod = $config->get('jwt.blacklist_grace_period', 0);
            if (is_string($gracePeriod)) {
                $config->set('jwt.blacklist_grace_period', (int) $gracePeriod);
            }
        });
    }
}