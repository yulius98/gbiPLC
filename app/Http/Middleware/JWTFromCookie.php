<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        // Skip JWT check untuk route Livewire file preview/upload
        if ($request->is('livewire/preview-file/*') || $request->is('livewire/upload-file')) {
            return $next($request);
        }

        // Log untuk memeriksa apakah token ditemukan di cookie
        if ($token = $request->cookie('jwt_token')) {
            Log::info('JWTFromCookie: Token ditemukan di cookie', ['token' => $token]);
            try {
                // Set token ke JWTAuth
                JWTAuth::setToken($token);

                // Verifikasi dan parse token (stateless)
                $user = JWTAuth::authenticate();

                if ($user) {
                    Log::info('JWTFromCookie: Token valid, pengguna terautentikasi', ['user_id' => $user->id, 'user_email' => $user->email]);

                    // Set user ke context tanpa session (stateless)
                    Auth::setUser($user);

                    // Tambahkan user ke request untuk controller
                    $request->setUserResolver(function () use ($user) {
                        return $user;
                    });
                } else {
                    Log::warning('JWTFromCookie: Token valid tetapi pengguna tidak ditemukan');
                }
            } catch (JWTException $e) {
                Log::error('JWTFromCookie: Token tidak valid atau kedaluwarsa', ['error' => $e->getMessage()]);

                // Hapus token dari request context
                $request->cookies->remove('jwt_token');
            }
        } else {
            Log::info('JWTFromCookie: Token tidak ditemukan di cookie');
        }

        return $next($request);
    }
}