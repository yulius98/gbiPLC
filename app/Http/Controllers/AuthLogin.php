<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            return match ($user->role) {
                'pengurus' => redirect('/pengurus/dashboard_admin/' . $user->name),
                'jemaat' => redirect('/jemaat/page-jemaat'),
                default => redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman admin')
            };
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('message', 'Successfully logged out');
    }
}
