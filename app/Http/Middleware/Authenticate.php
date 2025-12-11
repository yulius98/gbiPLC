<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Auth\AuthenticationException;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request): ?string
    {
        // Jika request ke API, jangan redirect, return null untuk trigger JSON response
        if ($request->is('api/*') || $request->expectsJson()) {
            return null;
        }

        return route('login');
    }

    /**
     * Handle an unauthenticated user.
     */
    protected function unauthenticated($request, array $guards)
    {
        // Untuk API routes, throw exception yang akan di-handle oleh exception handler
        // untuk return JSON response 401
        throw new AuthenticationException(
            'Unauthenticated.',
            $guards,
            $this->redirectTo($request)
        );
    }
}
