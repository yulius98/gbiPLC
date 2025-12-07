<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\TblEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $latestEvent = TblEvent::orderBy('tgl_event', 'desc')->first();

        if ($latestEvent) {
            $latestMonth = Carbon::parse($latestEvent->tgl_event)->month;
            $latestYear = Carbon::parse($latestEvent->tgl_event)->year;

            $Event = TblEvent::whereMonth('tgl_event', $latestMonth)
                ->whereYear('tgl_event', $latestYear)
                ->orderBy('tgl_event', 'asc')
                ->paginate(10);
        } else {
            $Event = collect(); // Tidak ada event sama sekali
        }

        // Tambahkan photo_url ke setiap user
        $data = $Event->map(function ($event) {
            $eventArr = $event->toArray();
            $eventArr['photo_url'] = $event->photo_url;
            return $eventArr;
        });    

        if ($Event->isNotEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => $data
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => []
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
