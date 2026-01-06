<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'email' => 'required|email',
            'alamat' => 'required',
            'no_HP' => 'required',
            'gol_darah' => 'required',
            'tgl_lahir' => 'required',
            'filename' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg', // maksimal 1MB (1024 KB)
        ], [
            'filename.image' => 'File yang diunggah harus berupa gambar.',
            'filename.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau svg.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validasi gagal',
                'errors' => $validator->errors()
            ],422);
        }

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



        $add_user->save();


        return response()->json([
            'status'=>true,
            'message'=>'Data berhasil disimpan',
            'data'=>$add_user
        ],200);


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
