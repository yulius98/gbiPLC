<?php

namespace App\Http\Controllers;

use App\Models\TblEvent;
use Carbon\Carbon;

use function Symfony\Component\Clock\now;

class JemaatController extends Controller
{
    /**
     * Display list of jemaat and current month events
     */
    public function index()
    {
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
        
        return view('jemaat', compact('events'));
    }
}
