<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthLogin extends Controller
{
    public function AuthUser(request $request){

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);


        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();


            $dtuser = User::where('email', $credentials['email'])->first();



            if ( $dtuser->role == "pengurus") {
                return redirect('/pengurus/dashboard_admin/'.$dtuser->name);
            } elseif ( $dtuser->role == "jemaat"){
                return redirect('/jemaat/page-jemaat');
            }

            // If user role is not pengurus, redirect to homepage
            return redirect('/')->with('message', 'Anda tidak memiliki akses ke halaman admin');

        }

        return back()->withErrors([
            'email' => 'Email atau Password Salah !!',
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
