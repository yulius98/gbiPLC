<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class DataJemaat extends Component
{
    use WithPagination, WithFileUploads; 
public $name, $email, $password, $alamat, $no_HP, $gol_darah, $filename = null, $role;
    protected $paginationTheme = 'bootstrap';
    public $updatedata = false;
    public $jemaat_id;
    public $cari; 
    public $sortcolom ='name'; 
    public $sortdirection = 'asc';
    
     public function show_detail($id)
    {
        
        $jemaat = User::where('users.id', $id)
            ->first();
        

            $this->name = $jemaat->name;
            $this->email = $jemaat->emai;
            $this->password = $jemaat->password;
            $this->alamat = $jemaat->alamat;
            $this->no_HP = $jemaat->no_HP;
            $this->gol_darah = $jemaat->gol_darah;
            $this->filename = $jemaat->filename;
            
            $this->updatedata = true;
            $this->jemaat_id = $id;
       
    }

    public function simpan()
    {
        $rules = [
            'name' => 'required',
            'filename' => 'nullable|image|max:2048', // 1MB Max
            
        ];
        $messages = [
            'name' => 'Nama tidak boleh kosong',
            'filename.image' => 'File harus berupa gambar',
            'filename.max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
            
        ];
        $validated = $this->validate($rules, $messages);
        

        // Simpan data ke database
        $data = [
            'name' => $this->name,
            'email' => $this-> email,
            'password' => $this->password,
            'alamat' => $this-> alamat,
            'no_HP' => $this-> no_HP,
            'gol_darah' => $this-> gol_darah,
            'filename' => $this-> filename,
            'role' => $this->role,
        ];

        // Cek apakah ada gambar yang diupload
        if ($this->filename != null) {
            $data['filename'] = $this->filename->store('foto-jemaat', 'public');
        } else {
            $data['filename'] = null; // atau bisa dihapus jika tidak ingin menyimpan key 'gambar' sama sekali
        }

        // Simpan ke database
        User::create($data);
        session()->flash('message', 'Data Jemaat berhasil disimpan.');
        $this->clear();
        // Initialization code can go here
    }

    public function edit($id)
    {    
        $jemaat = User::where('tblbarangs.id', $id)
            ->first();
        //dd($barang);

        $this->name = $jemaat->name;
        $this-> email = $jemaat-> email;
        $this-> password = $jemaat-> password;
        $this-> alamat = $jemaat-> alamat;
        $this-> no_HP = $jemaat-> no_HP;
        $this-> gol_darah = $jemaat-> gol_darah;
        $this-> filename = $jemaat-> filename;
        $this-> role = $jemaat-> role;     
        $this->updatedata = true;
        $this->jemaat_id = $id;
    }

    public function update()
    {
        $rules = [
            'name' => 'required',
            'filename' => 'nullable|image|max:2048', // 1MB Max
            
        ];
        $messages = [
            'name' => 'Nama tidak boleh kosong',
            'filename.image' => 'File harus berupa gambar',
            'filename.max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
            
        ];
        $validated = $this->validate($rules, $messages);
        
        $jemaat = User::find($this->jemaat_id);

        // Simpan gambar jika ada upload baru
        if ($this->filename) {
            $path = $this->gambar->store('foto-jemaat','public'); // Simpan di storage/app/barang
            $jemaat->gambar = $path;
        }

        // Update field lainnya
        $jemaat->name = $this->name;
        $jemaat-> email = $this-> email;
        $jemaat-> password = $this-> password;
        $jemaat-> alamat = $this-> alamat;
        $jemaat-> no_HP = $this-> no_HP;
        $jemaat-> gol_darah = $this-> gol_darah;
        $jemaat-> role = $this-> role;
        $jemaat-> save();

        session()->flash('message', 'Data Jemaat berhasil diupdate.');
        $this->clear();
    }

    public function clear()
    {
        $this->name = '';
        $this-> email = '';
        $this-> password = '';
        $this-> alamat = '';
        $this-> no_HP = '';
        $this-> gol_darah = '';
        $this-> role = '';
        $this-> filename = '';
        $this->cari = '';
        
        $this->updatedata = false;
    }

    public function hapus()
    {
        
        $id = $this->jemaat_id;
        $jemaat = User::find($id);
        if ($jemaat) {
            
            // Hapus gambar dari storage
            $gambarPath = storage_path('app/public' . $jemaat->filename);
            if (file_exists($gambarPath)) {
                unlink($gambarPath);
            }
            // Hapus data dari database
            $jemaat->delete();
            session()->flash('message', 'Data Jemaat berhasil dihapus.');
        } else {
            session()->flash('message', 'Data Jemaat tidak ditemukan.');
        }

        $this->clear();
    }

    public function konfimasihapus($id)
    {
        
        $this->jemaat_id = $id;
    }

    public function sort($colomname){
        
        $this->sortcolom = $colomname;
        //dump($this->sortcolom);
        $this->sortdirection = $this->sortdirection == 'asc' ? 'desc' : 'asc';
        //dd($this->sortdirection);
        
    }
    public function render()
    {
        if ($this->cari != null) {
            $dtjemaat = User::where('users.name', 'like', '%' . $this->cari . '%')
            ->orderBy($this->sortcolom,$this->sortdirection)->paginate(20);
        } else {
            $dtjemaat = User::orderBy($this->sortcolom,$this->sortdirection)
            ->paginate(10);
        }

        return view('livewire.data-jemaat',['dtjemaat' => $dtjemaat]);
    }
}