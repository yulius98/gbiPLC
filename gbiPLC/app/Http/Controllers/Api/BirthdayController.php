<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class BirthdayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $birthdayMembers = User::whereMonth('tgl_lahir', Carbon::now()->month)
            ->orderby('tgl_lahir','asc')
            ->get();

        // Tambahkan photo_url ke setiap user
        $data = $birthdayMembers->map(function ($user) {
            $userArr = $user->toArray();
            $userArr['photo_url'] = $user->photo_url;
            return $userArr;
        });

        if ($birthdayMembers->isNotEmpty()) {
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
