<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RegController extends Controller
{
     public function RegJemaat(Request $request){
        //dd($request);

        // Validasi request
        $request->validate([
        'filename' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // maksimal 2MB (2048 KB)
        ], [
        'filename.image' => 'File yang diunggah harus berupa gambar.',
        'filename.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau svg.',
        'filename.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);


        $add_user = new User(); 
        $add_user->name = $request->name;
        $add_user->tgl_lahir = $request->tgl_lahir;
        $add_user->no_HP = $request->no_HP;
        $add_user->gol_darah = $request->gol_darah;
        $add_user->alamat = $request->alamat;
        $add_user->role = 'jemaat';
        
        //gunanakan jika pakai disave local
        if ($request->hasFile('filename')) {
                $add_user->filename = $request->file('filename')->store('foto-jemaat', 'public');
        } else {
                $add_user->filename = null;
        }

        
        //gunakan jika pakai laravel cloud/
        //$file = $request->file('file');
        //$fileName = time().'_'.$file->getClientOriginalName();
        //$path = Storage::disk('s3')->putFileAs('',$file,$file);
        $add_user->save();


        $dtjemaat = User::where('role','=','jemaat')
                ->orderby('name','asc')
                ->paginate(8);

        //return view('jemaat',['dtjemaat' => $dtjemaat]);
       return view ('jemaat.daftar');
        
    }

    public function showJemaat(){
        $dtjemaat = User::where('role','=','jemaat')
                ->orderby('name','asc')
                ->paginate(8);

        return view('jemaat',['dtjemaat' => $dtjemaat]);
    }
}