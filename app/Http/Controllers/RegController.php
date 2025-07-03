<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RegController extends Controller
{
     public function RegJemaat(Request $request){
        //dd($request);
        $add_user = new User(); 
        $add_user->name = $request->name;
        $add_user->tgl_lahir = $request->tgl_lahir;
        $add_user->no_HP = $request->no_HP;
        $add_user->gol_darah = $request->gol_darah;
        $add_user->alamat = $request->alamat;
        $add_user->role = 'jemaat';
        $add_user->foto = $request->file('foto')->store('foto-jemaat', 'public');
        $add_user->save();

        $dtjemaat = User::where('role','=','jemaat')
                ->orderby('name','asc')
                ->paginate(8);

        return view('jemaat',['dtjemaat' => $dtjemaat]);
        
    }

    public function showJemaat(){
        $dtjemaat = User::where('role','=','jemaat')
                ->orderby('name','asc')
                ->paginate(8);

        return view('jemaat',['dtjemaat' => $dtjemaat]);
    }
}