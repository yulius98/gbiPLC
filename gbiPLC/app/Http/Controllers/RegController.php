<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\TblCarousel;
use App\Models\TblPastorNote;
use App\Models\TblEvent;




class RegController extends Controller
{
     public function RegJemaat(Request $request){
        //dd($request);

        // Validasi request
        $request->validate([
        'filename' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024', // maksimal 1MB (1024 KB)
        ], [
        'filename.image' => 'File yang diunggah harus berupa gambar.',
        'filename.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau svg.',
        'filename.max' => 'Ukuran gambar tidak boleh lebih dari 1MB.',
        ]);


        $add_user = new User();
        $add_user->name = $request->name;
        $add_user->tgl_lahir = $request->tgl_lahir;
        $add_user->no_HP = $request->no_HP;
        $add_user->gol_darah = $request->gol_darah;
        $add_user->alamat = $request->alamat;
        $add_user->email = $request->email;
        $add_user->facebook = $request->facebook;
        $add_user->instagram = $request->instagram;
        $add_user->role = 'jemaat';

        //gunanakan jika pakai disave local
        if ($request->hasFile('filename')) {
                $file = $request->file('filename');
                // Sanitize the name input to create a safe filename
                $name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->name);
                $extension = $file->getClientOriginalExtension();
                $filename = $name . '.' . $extension;
                $add_user->filename = $file->storeAs('foto-jemaat', $filename, 'public');
        } else {
                $add_user->filename = null;
        }


        //gunakan jika pakai laravel cloud/
        //$file = $request->file('file');
        //$fileName = time().'_'.$file->getClientOriginalName();
        //$path = Storage::disk('s3')->putFileAs('',$file,$file);

        $add_user->save();

        $dtcarousel = cache()->remember('dtcarousel', 600, function () {
            return TblCarousel::all();
        });

        $dtpasstornote = cache()->remember('dtpasstornote', 600, function () {
            return TblPastorNote::orderBy('tgl_note', 'desc')->first();
        });

        $dtjemaatultah = cache()->remember('dtjemaatultah_' . Carbon::now()->month, 600, function () {
            return User::whereMonth('tgl_lahir', Carbon::now()->month)
                ->where('role', 'jemaat')
                ->orderby('name', 'asc')
                ->paginate(8);
        });

        return redirect('/')->with('success', 'Pendaftaran berhasil! Selamat datang di GBI PLC');
       //return view('welcome', compact('dtcarousel','dtpasstornote','dtjemaatultah'));

    }

    public function showJemaat(){
        $events = TblEvent::orderBy('tgl_event', 'desc')
                ->paginate(8);

        return view('jemaat', ['events' => $events]);
    }
}
