<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\TblIbadahRaya;
use App\Http\Controllers\Controller;

class IbadahRayaController extends Controller
{
    public function getLink(Request $request) 
    {
        $ibadahRaya = TblIbadahRaya::where('tgl_ibadah', $request->tgl_ibadah)
                    ->where('ibadah_ke', $request->ibadah_ke)
                    ->first();
            
        return response()->json([
            'status' => true,
            'message' => 'Data ditemukan',
            'data' => $ibadahRaya
        ]);
                    
    }
}
