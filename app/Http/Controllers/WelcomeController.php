<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TblCarousel;
use App\Models\TblPastorNote;
use App\Models\TblPopupAds;
use App\Models\User;
use Carbon\Carbon;

class WelcomeController extends Controller
{
    public function index()
    {
        $dtpopup = TblPopupAds::all();
        
        $dtcarousel = TblCarousel::all();
        
        $dtpasstornote = TblPastorNote::orderBy('tgl_note', 'desc')
            ->first();
        
        $dtjemaatultah = User::whereMonth('tgl_lahir', Carbon::now()->month)
            ->orderby('tgl_lahir','asc')
            ->paginate(8);


        //$dtcarousel = cache()->remember('dtcarousel', 600, function () {
        //    return TblCarousel::all();
        //});

        //$dtpasstornote = cache()->remember('dtpasstornote', 600, function () {
        //    return TblPastorNote::orderBy('tgl_note', 'desc')->first();
        //});

        //$dtjemaatultah = cache()->remember('dtjemaatultah_' . Carbon::now()->month, 600, function () {
        //    return User::whereMonth('tgl_lahir', Carbon::now()->month)
        //        ->where('role', 'jemaat')
        //        ->orderby('name', 'asc')
        //        ->paginate(8);
        //});    

        return view('welcome', compact('dtpopup','dtcarousel','dtpasstornote','dtjemaatultah'));
    }
}