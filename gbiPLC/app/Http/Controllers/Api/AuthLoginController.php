<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class AuthLoginController extends Controller
{
    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        $credential = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if(! $token = JWTAuth::attempt($credential)) {
            return response()->json(['error' => 'Email atau Password salah'], 401);
        }

        return $this->respondWithToken($token);

    }

    public function refresh() {
        try {
            $newToken = JWTAuth::refresh();
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Token tidak valid'], 401);
        }
        return $this->respondWithToken($newToken);
    }

    public function logout() {
        JWTAuth::logout();
        return response()->json(['message'=>'Berhasil logout']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Return JWT token response
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 1,
            'refresh_ttl' => config('jwt.refresh_ttl') * 1
        ]);
    }
}
