<?php

namespace App\Livewire;

use App\Models\TblKunjungan;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;



class DashboardKunjunganJemaat extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        
        $dtjemaat_baru = User::where('role', 'jemaat')
            ->where('created_at', '>=', Carbon::now()->subMonth())
            ->orderBy('created_at', 'desc')
            ->paginate(5); 

        $jmljemaat_baru = User::where('role', 'jemaat')
            ->where('created_at', '>=', Carbon::now()->subMonth())
            ->count(); 

        $dtkunjungan_bln_ini = TblKunjungan::whereMonth('tglkunjungan', Carbon::now()->month)
            ->whereYear('tglkunjungan', Carbon::now()->year)
            ->count();

        $dtjemaat_belumpernah_kunjungan = User::leftJoin('tbl_kunjungans', 'users.id', '=', 'tbl_kunjungans.id_jemaat')
            ->where('users.role', 'jemaat')
            ->whereNull('tbl_kunjungans.id_jemaat')
            ->count();

        $jemaat_belumpernah_kunjungan = User::leftJoin('tbl_kunjungans', 'users.id', '=', 'tbl_kunjungans.id_jemaat')
            ->where('users.role', 'jemaat')
            ->whereNull('tbl_kunjungans.id_jemaat')
            ->paginate(5);
                
        $dtkunjungan_lebih_dari_satu = TblKunjungan::select('id_jemaat', DB::raw('COUNT(*) as total_kunjungan'))
            ->whereMonth('tglkunjungan', Carbon::now()->month)
            ->whereYear('tglkunjungan', Carbon::now()->year)
            ->groupBy('id_jemaat')
            ->having('total_kunjungan', '>', 1)
            ->count();


        return view('livewire.dashboard-kunjungan-jemaat',['jemaat_belumpernah_kunjungan' =>$jemaat_belumpernah_kunjungan,'dtjemaat_belumpernah_kunjungan' => $dtjemaat_belumpernah_kunjungan,'jmljemaat_baru'=> $jmljemaat_baru,'dtkunjungan_bln_ini' => $dtkunjungan_bln_ini, 'dtkunjungan_lebih_dari_satu' => $dtkunjungan_lebih_dari_satu, 'dtjemaat_baru'=>$dtjemaat_baru]);
            
    }
}