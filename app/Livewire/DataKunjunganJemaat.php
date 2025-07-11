<?php

namespace App\Livewire;

use App\Models\TblKunjungan;
use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class DataKunjunganJemaat extends Component
{
    use WithPagination, WithFileUploads; 
    public $name, $id_jemaat, $tglkunjungan, $nama_timbesuk, $filename, $keterangan;
    protected $paginationTheme = 'bootstrap';
    public $updatedata = false;
    public $kunjungan_id;
    public $cari; 
    public $sortcolom ='tglkunjungan'; 
    public $sortdirection = 'asc';

    public function selectJemaat($id)
    {
        $user = User::find($id);
        if ($user) {
            $this->id_jemaat = $id;
            $this->name = $user->name;
        }
    }

    
    public function show_detail($id)
    {
        
        $kunjungan = TblKunjungan::join('users as jemaat','jemaat.id','=','tbl_kunjungans.id_jemaat')
            ->select('jemaat.*','pengurus1.*','pengurus2.*','pengurus3.*','tbl_kunjungans.*')
            ->where('tbl_kunjungans.id',$id)
            ->first();

            $this->name = $kunjungan->name;
            $this->tglkunjungan = $kunjungan->tglkunjungan;
            $this->nama_timbesuk = $kunjungan->id_pengurus1;
            $this->keterangan = $kunjungan->keterangan;
            
            $this->updatedata = true;
            $this->kunjungan_id = $id;
       
    }

    public function simpan()
    {
        $rules = [
            'filename' => 'nullable|image|max:2048', // 1MB Max
            
        ];
        $messages = [
            'filename.image' => 'File harus berupa gambar',
            'filename.max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
            
        ];
        $validated = $this->validate($rules, $messages);
        

        // Simpan data ke database
        $data = [
            'id_jemaat' => $this->id_jemaat,
            'tglkunjungan' => $this->tglkunjungan,
            'nama_timbesuk' => $this->nama_timbesuk,
            'filename' =>$this->filename,
            'keterangan' => $this->keterangan,
            
        ];

        // Cek apakah ada gambar yang diupload
        if ($this->filename != null) {
            $data['filename'] = $this->filename->store('foto-jemaat', 'public');
        } else {
            $data['filename'] = null; // atau bisa dihapus jika tidak ingin menyimpan key 'gambar' sama sekali
        }

        // Simpan ke database
        TblKunjungan::create($data);
        session()->flash('message', 'Data Kunjungan Jemaat berhasil disimpan.');
        $this->clear();
        // Initialization code can go here
    }

    public function edit($id)
    {    
        $kunjungan = TblKunjungan::where('tgl_kunjungans.id', $id)
            ->first();
        //dd($barang);

        $this->id_jemaat = $kunjungan->id_jemaat;
        $this-> tglkunjungan = $kunjungan-> tglkunjungan;
        $this-> nama_timbesuk = $kunjungan-> nama_timbesuk;
        $this-> filename = $kunjungan-> filename;
        $this-> keterangan = $kunjungan-> keterangan;     
        $this->updatedata = true;
        $this->kunjungan_id = $id;
    }

    public function update()
    {
        $rules = [
            'filename' => 'nullable|image|max:2048', // 1MB Max
            
        ];
        $messages = [
            'filename.image' => 'File harus berupa gambar',
            'filename.max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
            
        ];
        $validated = $this->validate($rules, $messages);
        
        $kunjungan = TblKunjungan::find($this->kunjungan_id);

        // Simpan gambar jika ada upload baru
        if($kunjungan->filename != null) {
            $gambarPath = storage_path('app/public/' . $kunjungan->filename);
            if (file_exists($gambarPath)) {
                unlink($gambarPath);
            }
        }

        if ($this->filename) {
            $path = $this->filename->store('foto-kunjungan','public'); 
            $kunjungan->filename = $path;
        }

        // Update field lainnya
        $kunjungan->id_jemaat = $this->id_jemaat;
        $kunjungan-> tglkunjungan = $this-> tglkunjungan;
        $kunjungan-> nama_timbesuk = $this-> nama_timbesuk;
        $kunjungan-> keterangan = $this-> keterangan;
        
        $kunjungan-> save();

        session()->flash('message', 'Data Kunjungan Jemaat berhasil diupdate.');
        $this->clear();
    }

    public function clear()
    {
        $this->name = '';
        $this-> tglkunjungan = '';
        $this-> nama_timbesuk = '';
        $this-> filename = '';
        $this-> keterangan = '';
        $this->cari = '';
        
        $this->updatedata = false;
    }

    public function hapus()
    {
        
        $id = $this->kunjungan_id;
        $kunjungan = TblKunjungan::find($id);
        if ($kunjungan) {
            
            // Hapus gambar dari storage
            $gambarPath = storage_path('app/public/' . $kunjungan->filename);
            if (file_exists($gambarPath)) {
                unlink($gambarPath);
            }
            // Hapus data dari database
            $kunjungan->delete();
            session()->flash('message', 'Data Kunjungan Jemaat berhasil dihapus.');
        } else {
            session()->flash('message', 'Data Kunjungan Jemaat tidak ditemukan.');
        }

        $this->clear();
    }

    public function konfimasihapus($id)
    {
        
        $this->kunjungan_id = $id;
    }

    public function sort($colomname){
        
        $this->sortcolom = $colomname;
        //dump($this->sortcolom);
        $this->sortdirection = $this->sortdirection == 'asc' ? 'desc' : 'asc';
        //dd($this->sortdirection);
        
    }

    public function kunjungi($id)
    {
        $kunjungan = User::find($id);
        if ($kunjungan) {
            $this->id_jemaat = $kunjungan->id;
            $this->name = $kunjungan->name;
        } else {
            session()->flash('message', 'Data Jemaat tidak ditemukan.');
        }
    }

    public function render()
    {
                         
        if($this->cari != null) {
            $dtkunjungan = TblKunjungan::join('users as jemaat','jemaat.id','=','tbl_kunjungans.id_jemaat')
                        ->select('jemaat.*','tbl_kunjungans.*')
                        ->where('jemaat.name', 'like', '%' . $this->cari . '%')
                        ->orderBy($this->sortcolom,$this->sortdirection)->paginate(20);
        } else {
            $dtkunjungan = TblKunjungan::join('users as jemaat','jemaat.id','=','tbl_kunjungans.id_jemaat')
                        ->select('jemaat.*','tbl_kunjungans.*')
                        ->orderBy($this->sortcolom,$this->sortdirection)->paginate(20);
        }
        
        

        $dtpengurus = User::where('users.role','=','pengurus')
            ->get();

        $dtjemaat = User::where('users.role','=','jemaat')
            ->where('name', 'like', '%' . $this->cari . '%')
            ->orderBy('name', 'asc')
            ->paginate(1);   
            
        //dd($dtkunjungan);    

        return view('livewire.data-kunjungan-jemaat',['dtkunjungan'=> $dtkunjungan, 'dtpengurus'=>$dtpengurus,'dtjemaat'=>$dtjemaat]);
    }
}