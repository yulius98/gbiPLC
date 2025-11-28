<?php

namespace App\Http\Controllers;

use App\Models\TblYouthProgram;
use Illuminate\Http\Request;

class YouthProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programs = TblYouthProgram::active()->ordered()->get();
        return view('program', compact('programs'));

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
            'icon' => 'nullable|string',
            'frequency' => 'required|string',
            'category' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'integer'
        ]);

        TblYouthProgram::create($validated);
        return redirect()->back()->with('success', 'Program berhasil ditambahkan!');

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
