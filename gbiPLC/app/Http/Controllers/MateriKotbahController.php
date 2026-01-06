<?php

namespace App\Http\Controllers;

use App\Models\TblMateriKotbah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriKotbahController extends Controller
{
    public function index()
    {
        return view('materi_kotbah');
    }

    public function download($id)
    {
        $materiKotbah = TblMateriKotbah::findOrFail($id);
        
        if ($materiKotbah->filename) {
            $filePath = storage_path('app/public/' . $materiKotbah->filename);
            
            if (file_exists($filePath)) {
                $fileName = pathinfo($materiKotbah->filename, PATHINFO_BASENAME);
                $originalName = $materiKotbah->judul . '.' . pathinfo($materiKotbah->filename, PATHINFO_EXTENSION);
                
                return response()->download($filePath, $originalName);
            }
        }
        
        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }
}