<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthLogin extends Controller
{
    /**
     * Authenticate user and redirect based on role
     */
    public function authenticateUser(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Manual authentication - cek credentials tanpa session
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user || !password_verify($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'Email atau Password Salah']);
        }
        
        // Generate JWT token langsung dari user
        try {
            $token = JWTAuth::fromUser($user);
        } catch (\Exception $e) {
            Log::error('JWT Token Generation Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            Log::info('Public Key Path: ' . config('jwt.keys.public'));
            Log::info('Private Key Path: ' . config('jwt.keys.private'));
            return back()->withErrors(['email' => 'Error saat membuat session. Silakan coba lagi.']);
        }
        
        // Set JWT ke HTTP Only cookie - ini adalah authentication utama
        $cookie = cookie(
            'jwt_token',
            $token,
            60, // TTL dalam menit, hardcode untuk debugging
            '/',               // Path
            null,              // Domain
            false,             // Secure (set true untuk production dengan HTTPS)
            true,              // HTTP Only
            false,             // Raw
            'lax'              // SameSite
        );

        // Redirect berdasarkan role dengan cookie JWT (no session)
        $redirectResponse = match ($user->role) {
            'pengurus' => redirect('/pengurus/dashboard_admin/' . $user->name),
            'jemaat' => redirect('/jemaat/page-jemaat'),
            default => redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman admin')
        };
        
        // Attach cookie ke response
        return $redirectResponse->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        // Invalidate JWT token dari cookie
        $token = $request->cookie('jwt_token');
        if ($token) {
            try {
                \Tymon\JWTAuth\Facades\JWTAuth::setToken($token)->invalidate();
                Log::info('JWT Token berhasil di-invalidate', ['jti' => JWTAuth::getPayload($token)['jti']]);
            } catch (\Exception $e) {
                Log::warning('Gagal invalidate JWT token: ' . $e->getMessage());
            }
        }

        // Hapus JWT cookie dengan path dan domain eksplisit
        $cookie = cookie('jwt_token', '', -1, '/', null, false, true, false, 'lax');
        Log::info('Cookie jwt_token telah dihapus');

        // Hapus session Laravel tanpa memanggil Auth::logout() yang membutuhkan token di header
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('message', 'Successfully logged out')
            ->withCookie($cookie);
    }
}
