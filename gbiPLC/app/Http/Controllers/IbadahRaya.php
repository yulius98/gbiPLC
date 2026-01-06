<?php

namespace App\Http\Controllers;

use App\Models\TblIbadahRaya;
use Illuminate\Http\Request;

class IbadahRaya extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('ibadahraya', ['ibadahRaya' => null]);
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

    /**
     * Get link ibadah based on date and service number
     */
    public function getLink(Request $request) 
    {
        // Validasi input
        $request->validate([
            'tgl_ibadah' => 'required|date',
            'ibadah_ke' => 'required',
        ]);
        // Cari data ibadah berdasarkan tanggal dan ibadah ke
        $ibadahRaya = TblIbadahRaya::where('tgl_ibadah', $request->tgl_ibadah)
                    ->where('ibadah_ke', $request->ibadah_ke)
                    ->first();
        
        // Kembalikan view dengan data
        return view('ibadahraya', compact('ibadahRaya'));
    }
}
