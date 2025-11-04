<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class JWTFromCookie
{
    /**
     * Handle an incoming request.
     * Middleware untuk JWT murni (stateless) - tidak menggunakan session
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek JWT token di cookie
        if ($token = $request->cookie('jwt_token')) {
            try {
                // Set token ke JWTAuth
                JWTAuth::setToken($token);
                
                // Verifikasi dan parse token (stateless)
                $user = JWTAuth::parseToken()->authenticate();
                
                if ($user) {
                    // Set user ke context tanpa session (stateless)
                    Auth::setUser($user);
                    
                    // Tambahkan user ke request untuk controller
                    $request->setUserResolver(function () use ($user) {
                        return $user;
                    });
                }
            } catch (JWTException $e) {
                // Token tidak valid atau expired
                // Untuk protected routes, akan di-handle oleh auth middleware
                // Hapus token dari request context
                $request->cookies->remove('jwt_token');
            }
        }

        return $next($request);
    }
}