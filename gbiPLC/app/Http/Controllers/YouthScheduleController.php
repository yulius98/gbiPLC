<?php

namespace App\Http\Controllers;

use App\Models\TblYouthSchedule;
use Illuminate\Http\Request;

class YouthScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $weeklySchedules = TblYouthSchedule::active()->weekly()->ordered()->get();
        $specialEvents = TblYouthSchedule::active()->specialEvents()->get();
        
        return view('youth.schedule', compact('weeklySchedules', 'specialEvents'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:weekly,special_event',
            'day_of_week' => 'nullable|string',
            'event_date' => 'nullable|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string',
            'location_url' => 'nullable|url',
            'category' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'integer'
        ]);

        TblYouthSchedule::create($validated);
        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan!');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
