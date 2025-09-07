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
    public $name, $email, $password, $alamat, $no_HP, $gol_darah, $filename = null, $role, $tgl_lahir, $facebook, $instagram;
    public $foto_upload; // untuk upload file gambar
    protected $paginationTheme = 'bootstrap';
    public $updatedata = false;
    public $jemaat_id;
    public $cari;
    public $sortcolom ='name';
    public $sortdirection = 'asc';

    public function show_detail($id)
    {
        $jemaat = User::where('users.id', $id)->first();
        $this->name = $jemaat->name;
        $this->email = $jemaat->email;
        $this->password = $jemaat->password;
        $this->alamat = $jemaat->alamat;
        $this->no_HP = $jemaat->no_HP;
        $this->gol_darah = $jemaat->gol_darah;
        $this->filename = $jemaat->filename; // path gambar lama
        $this->foto_upload = null; // reset upload file
        $this->role = $jemaat->role;
        $this->tgl_lahir = $jemaat->tgl_lahir;
        $this->facebook = $jemaat->facebook;
        $this->instagram = $jemaat->instagram;
        $this->updatedata = true;
        $this->jemaat_id = $id;
    }

    public function simpan()
    {
        $rules = [
            'name' => 'required',
            'foto_upload' => 'nullable|image|max:1024', // 1MB Max
            'role' => 'required|in:jemaat,pengurus,pendeta',
        ];
        $messages = [
            'name' => 'Nama tidak boleh kosong',
            'foto_upload.image' => 'File harus berupa gambar',
            'foto_upload.max' => 'Ukuran gambar tidak boleh lebih dari 1MB',
            'role.required' => 'Role harus diisi',
            'role.in' => 'Role harus salah satu dari jemaat, pengurus, atau pendeta',
        ];
        $validated = $this->validate($rules, $messages);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'alamat' => $this->alamat,
            'no_HP' => $this->no_HP,
            'gol_darah' => $this->gol_darah,
            'tgl_lahir' => $this->tgl_lahir,
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'role' => $this->role,
        ];

        // Cek apakah ada gambar yang diupload
        if ($this->foto_upload) {
            $file = $this->foto_upload;
            $name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $this->name);
            $extension = $file->getClientOriginalExtension();
            $filename = $name . '.' . $extension;
            $data['filename'] = $file->storeAs('foto-jemaat', $filename, 'public');
        }

        User::create($data);
        session()->flash('message', 'Data Jemaat berhasil disimpan.');
        $this->clear();
    }

    public function edit($id)
    {
        $jemaat = User::where('users.id', $id)
            ->first();
        //dd($barang);

        $this->name = $jemaat->name;
        $this-> email = $jemaat-> email;
        $this-> password = $jemaat-> password;
        $this-> alamat = $jemaat-> alamat;
        $this-> no_HP = $jemaat-> no_HP;
        $this-> gol_darah = $jemaat-> gol_darah;
        $this-> filename = $jemaat-> filename;
        $this->tgl_lahir = $jemaat->tgl_lahir;
        $this->facebook = $jemaat->facebook;
        $this->instagram = $jemaat->instagram;
        $this-> role = $jemaat-> role;
        $this->updatedata = true;
        $this->jemaat_id = $id;
    }

    public function update()
    {
        $rules = [
            'name' => 'required',
            'foto_upload' => 'nullable|image|max:2048', // 2MB Max
            'role' => 'required|in:jemaat,pengurus,pendeta',
        ];
        $messages = [
            'name' => 'Nama tidak boleh kosong',
            'foto_upload.image' => 'File harus berupa gambar',
            'foto_upload.max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
            'role.required' => 'Role harus diisi',
            'role.in' => 'Role harus salah satu dari jemaat, pengurus, atau pendeta',
        ];
        $validated = $this->validate($rules, $messages);

        $jemaat = User::find($this->jemaat_id);

        // Simpan gambar jika ada upload baru
        if ($this->foto_upload) {
            // Hapus gambar lama jika ada
            if ($jemaat->filename) {
                $gambarPath = storage_path('app/public/' . $jemaat->filename);
                if (file_exists($gambarPath)) {
                    unlink($gambarPath);
                }
            }
            $file = $this->foto_upload;
            $name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $this->name);
            $extension = $file->getClientOriginalExtension();
            $filename = $name . '.' . $extension;
            $jemaat->filename = $file->storeAs('foto-jemaat', $filename, 'public');
        }

        // Update field lainnya
        $jemaat->name = $this->name;
        $jemaat->email = $this->email;
        $jemaat->password = $this->password;
        $jemaat->alamat = $this->alamat;
        $jemaat->no_HP = $this->no_HP;
        $jemaat->gol_darah = $this->gol_darah;
        $jemaat->tgl_lahir = $this->tgl_lahir;
        $jemaat->facebook = $this->facebook;
        $jemaat->instagram = $this->instagram;
        $jemaat->role = $this->role;
        $jemaat->save();

        session()->flash('message', 'Data Jemaat berhasil diupdate.');
        $this->clear();
    }

    public function clear()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->alamat = '';
        $this->no_HP = '';
        $this->gol_darah = '';
        $this->role = '';
        $this->filename = '';
        $this->foto_upload = null;
        $this->tgl_lahir = '';
        $this->facebook = '';
        $this->instagram = '';
        $this->cari = '';
        $this->updatedata = false;
    }

    public function hapus()
    {

        $id = $this->jemaat_id;
        $jemaat = User::find($id);
        if ($jemaat) {

            // Hapus gambar dari storage
            $gambarPath = storage_path('app/public/' . $jemaat->filename);
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
