<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MyProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $myprofile = User::find($id);

        if (!$myprofile) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => []
            ], 404);
        }

        // Karena $myprofile adalah single model, tidak perlu map()
        $data = $myprofile->toArray();
        $data['photo_url'] = $myprofile->photo_url;

        return response()->json([
            'status' => true,
            'message' => 'Data ditemukan',
            'data' => $data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $myprofile = User::find($id);

        if ($myprofile) {
            return response()->json([
                'status' => true,
                'message' => 'Data ditemukan',
                'data' => $myprofile
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan',
                'data' => []
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'sometimes|required',
            'email' => 'sometimes|required|email',
            'alamat' => 'sometimes|required',
            'no_HP' => 'sometimes|required',
            'gol_darah' => 'sometimes|required',
            'tgl_lahir' => 'sometimes|required|date',
            'facebook' => 'nullable|string',
            'instagram' => 'nullable|string',
            'filename' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'filename.image' => 'File yang diunggah harus berupa gambar.',
            'filename.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau svg.',
            'filename.max' => 'Ukuran file maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validasi gagal',
                'errors' => $validator->errors()
            ],422);
        }

        $myprofile = User::find($id);

        if (!$myprofile) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan',
            ],404);
        }

        // Update hanya field yang dikirim
        if ($request->has('name')) {
            $myprofile->name = $request->name;
        }

        if ($request->has('email')) {
            $myprofile->email = $request->email;
        }

        if ($request->has('alamat')) {
            $myprofile->alamat = $request->alamat;
        }

        if ($request->has('no_HP')) {
            $myprofile->no_HP = $request->no_HP;
        }

        if ($request->has('gol_darah')) {
            $myprofile->gol_darah = $request->gol_darah;
        }

        if ($request->has('tgl_lahir')) {
            $myprofile->tgl_lahir = $request->tgl_lahir;
        }

        if ($request->has('facebook')) {
            $myprofile->facebook = $request->facebook;
        }

        if ($request->has('instagram')) {
            $myprofile->instagram = $request->instagram;
        }

        // Handle file upload
        if ($request->hasFile('filename')) {
            // Hapus foto lama jika ada
            if ($myprofile->filename && Storage::disk('public')->exists($myprofile->filename)) {
                Storage::disk('public')->delete($myprofile->filename);
            }

            $file = $request->file('filename');
            // Sanitize the name input to create a safe filename
            $name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $myprofile->name);
            $extension = $file->getClientOriginalExtension();
            $filename = $name . '_' . time() . '.' . $extension;
            $myprofile->filename = $file->storeAs('foto-jemaat', $filename, 'public');
        }

        $myprofile->save();

        return response()->json([
            'status'=>true,
            'message'=>'Data berhasil diperbarui',
            'data'=>$myprofile
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
