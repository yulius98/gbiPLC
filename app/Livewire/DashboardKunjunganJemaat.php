<?php

namespace App\Livewire;

use App\Models\TblKunjungan;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



class DashboardKunjunganJemaat extends Component
{
    public function render()
    {
        $dtkunjungan_bln_ini = TblKunjungan::whereMonth('tglkunjungan', Carbon::now()->month)
            ->whereYear('tglkunjungan', Carbon::now()->year)
            ->count();

        $dtkunjungan_lebih_dari_satu = TblKunjungan::select('id_jemaat', DB::raw('COUNT(*) as total_kunjungan'))
            ->whereMonth('tglkunjungan', Carbon::now()->month)
            ->whereYear('tglkunjungan', Carbon::now()->year)
            ->groupBy('id_jemaat')
            ->having('total_kunjungan', '>', 1)
            ->count();


        return view('livewire.dashboard-kunjungan-jemaat',['dtkunjungan_bln_ini' => $dtkunjungan_bln_ini, 'dtkunjungan_lebih_dari_satu' => $dtkunjungan_lebih_dari_satu ]);
    }
}