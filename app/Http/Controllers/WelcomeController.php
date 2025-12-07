<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\TblEvent;
use App\Models\TblCarousel;
use App\Models\TblPopupAds;
use Illuminate\Http\Request;
use App\Models\TblPastorNote;

class WelcomeController extends Controller
{
    public function index()
    {
        $popupAds = TblPopupAds::all();

        $carousels = TblCarousel::all();

        $latestPastorNote = TblPastorNote::orderBy('tgl_note', 'desc')
            ->first();

        $latestEvent = TblEvent::orderBy('tgl_event', 'desc')->first();

        if ($latestEvent) {
            $latestMonth = Carbon::parse($latestEvent->tgl_event)->month;
            $latestYear = Carbon::parse($latestEvent->tgl_event)->year;

            $events = TblEvent::whereMonth('tgl_event', $latestMonth)
                ->whereYear('tgl_event', $latestYear)
                ->orderBy('tgl_event', 'asc')
                ->paginate(10);
        } else {
            $events = collect(); // Tidak ada event sama sekali
        }    

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

        return view('welcome', compact('popupAds','carousels','latestPastorNote','birthdayMembers', 'events'));
    }
}
