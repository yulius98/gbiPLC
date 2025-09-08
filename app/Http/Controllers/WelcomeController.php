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
        $popupAds = TblPopupAds::all();

        $carousels = TblCarousel::all();

        $latestPastorNote = TblPastorNote::orderBy('tgl_note', 'desc')
            ->first();

        $birthdayMembers = User::whereMonth('tgl_lahir', Carbon::now()->month)
            ->orderby('tgl_lahir','asc')
            ->paginate(8);


        //$carousels = cache()->remember('carousels', 600, function () {
        //    return TblCarousel::all();
        //});

        //$latestPastorNote = cache()->remember('latestPastorNote', 600, function () {
        //    return TblPastorNote::orderBy('tgl_note', 'desc')->first();
        //});

        //$birthdayMembers = cache()->remember('birthdayMembers_' . Carbon::now()->month, 600, function () {
        //    return User::whereMonth('tgl_lahir', Carbon::now()->month)
        //        ->where('role', 'jemaat')
        //        ->orderby('name', 'asc')
        //        ->paginate(8);
        //});

        return view('welcome', compact('popupAds','carousels','latestPastorNote','birthdayMembers'));
    }
}
