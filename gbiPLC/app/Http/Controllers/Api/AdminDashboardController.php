<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $user = User::find($id);
        $jmljemaat = User::count();
        $jmljemaat_baru = User::where('role', 'jemaat')
            ->where('created_at', '>=', Carbon::now()->subMonth())
            ->count();

        return response()->json([
            'status' => true,
            'username' => $user->name,
            'jumlah_jemaat' => $jmljemaat,
            'jumlah_jemaat_baru' => $jmljemaat_baru
        ]);     
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
