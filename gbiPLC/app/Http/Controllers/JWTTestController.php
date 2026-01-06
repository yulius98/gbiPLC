<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTTestController extends Controller
{
    /**
     * Test JWT token functionality (Pure JWT - No Session)
     */
    public function testToken(Request $request)
    {
        $data = [
            'auth_method' => 'Pure JWT (Stateless)',
            'jwt_authenticated' => Auth::check(),
            'current_user' => Auth::user(),
            'cookie_token_exists' => $request->hasCookie('jwt_token'),
            'jwt_user' => null,
            'token_valid' => false,
            'guard_used' => 'web (jwt-cookie driver)',
        ];

        // Test JWT token from cookie
        if ($token = $request->cookie('jwt_token')) {
            try {
                JWTAuth::setToken($token);
                $jwtUser = JWTAuth::parseToken()->authenticate();
                $data['jwt_user'] = [
                    'id' => $jwtUser->id,
                    'name' => $jwtUser->name,
                    'email' => $jwtUser->email,
                    'role' => $jwtUser->role,
                ];
                $data['token_valid'] = true;
                $data['token_claims'] = JWTAuth::getPayload()->toArray();
            } catch (\Exception $e) {
                $data['jwt_error'] = $e->getMessage();
            }
        }

        return response()->json($data, 200, [], JSON_PRETTY_PRINT);
    }
}