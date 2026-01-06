<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\TblMateriKotbah;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class MateriKotbahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $judul_kotbah = TblMateriKotbah::select('judul')
                        ->get();
        if ($judul_kotbah) {
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => $judul_kotbah
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

    public function GetLinkKotbah(Request $request)
    {
        $linkkotbah = TblMateriKotbah::where('tgl_kotbah', $request->tgl_kotbah)
                        ->get();

        if ($linkkotbah->isNotEmpty()) {
            // Transform data untuk menambahkan materi_kotbah_url
            $data = $linkkotbah->map(function ($kotbah) {
                $kotbahArr = $kotbah->toArray();
                $kotbahArr['materi_kotbah_url'] = url('storage/' . $kotbah->filename);
                return $kotbahArr;
            });
            
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
}
