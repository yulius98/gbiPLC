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
   
        $events = TblEvent::whereMonth('tgl_event', Carbon::now()->month)
            ->orderBy('tgl_event', 'asc')
            ->paginate(10);

        return view('jemaat', compact('events'));
    }
}
