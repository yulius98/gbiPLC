<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TblKomsel;
use Illuminate\Http\Request;

class LifeGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lifegroup = TblKomsel::orderBy('nama_komsel','asc')
                        ->get();
        if ($lifegroup) {
            // Transform data untuk menambahkan image_kotbah_url
            
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => $lifegroup
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak tersedia',
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
}
