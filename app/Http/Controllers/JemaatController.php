<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class JemaatController extends Controller
{
    public function Data_Jemaat(){

        $dtjemaat = User::where('role','=','jemaat')
                ->orderby('name','asc')
                ->paginate(8);
        
        return view ('jemaat',['dtjemaat' => $dtjemaat]);            
    }
}