<?php

namespace App\Http\Controllers;

use App\Models\TblMateriKomsel;
use Illuminate\Http\Request;

class MateriKomselController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $judul = TblMateriKomsel::orderBy('judul','asc')->paginate(10);
        return view('materi_komsel', compact('judul'));
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

    public function getLink(Request $request) 
    {
        // Validasi input
        $request->validate([
            'tgl_komsel' => 'required|date',
            'judul' => 'required',
        ]);
        
        // Ambil daftar judul untuk dropdown
        $judul = TblMateriKomsel::select('judul')->distinct()->orderBy('judul', 'asc')->get();
        
        // Cari data materi berdasarkan tanggal dan judul
        $materi = TblMateriKomsel::where('tgl_komsel', $request->tgl_komsel)
                    ->where('judul', $request->judul)
                    ->first();
        
        // Kembalikan view dengan data
        return view('materi_komsel', compact('materi', 'judul'));
    }
}
