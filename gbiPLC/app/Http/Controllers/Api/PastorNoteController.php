<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TblPastorNote;
use Illuminate\Http\Request;

class PastorNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $PastorNote = TblPastorNote::orderBy('tgl_note','desc')
            ->first();

        if ($PastorNote) {
            // Transform data untuk menambahkan image_kotbah_url
            $imageArr = $PastorNote->toArray();
            $imageArr['image_kotbah_url'] = url('storage/' . $PastorNote->filename);
            
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => $imageArr
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
