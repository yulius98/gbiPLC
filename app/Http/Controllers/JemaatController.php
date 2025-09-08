<?php

namespace App\Http\Controllers;

use App\Models\TblEvent;
use Carbon\Carbon;

class JemaatController extends Controller
{
    /**
     * Display list of jemaat and current month events
     */
    public function index()
    {
        $events = cache()->remember(
            'monthly_events_' . Carbon::now()->month . '_' . Carbon::now()->year,
            now()->addHours(1),
            function () {
                return TblEvent::thisMonth()
                    ->orderBy('tgl_event', 'asc')
                    ->paginate(10);
            }
        );

        return view('jemaat', compact('events'));
    }
}
