<?php

namespace App\Http\Controllers;

use App\Models\TblEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JemaatController extends Controller
{
    public function Data_Jemaat()
    {

        $dtevent = cache()->remember('dtevent' . Carbon::now()->month, 600, function () {
            return TblEvent::whereMonth('tgl_event', Carbon::now()->month)
                ->paginate(10);
        });

        return view ('jemaat',['dtevent' => $dtevent]);            
    }
}