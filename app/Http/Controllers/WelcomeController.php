<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TblCarousel;
use App\Models\TblPastorNote;
use App\Models\User;
use Carbon\Carbon;

class WelcomeController extends Controller
{
    public function index()
    {
        $dtcarousel = TblCarousel::all();
        
        $dtpasstornote = TblPastorNote::orderBy('tgl_note', 'desc')
            ->first();
        
        $dtjemaatultah = User::whereMonth('tgl_lahir', Carbon::now()->month)
            ->where('role', 'jemaat')
            ->orderby('name','asc')
            ->paginate(8);

        return view('welcome', compact('dtcarousel','dtpasstornote','dtjemaatultah'));
    }
}