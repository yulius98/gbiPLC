<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class DataJemaat extends Component
{
    use WithPagination, WithFileUploads;

    // Form properties
    public $name = '';
    public $email = '';
    public $password = '';
    public $alamat = '';
    public $no_HP = '';
    public $gol_darah = '';
    public $filename = null;
    public $role = '';
    public $tgl_lahir = '';
    public $facebook = '';
    public $instagram = '';
    public $foto_upload;

    // Component state
    public $updatedata = false;
    public $jemaat_id;
    public $cari = '';
    public $sortcolom = 'name';
    public $sortdirection = 'asc';

    protected $paginationTheme = 'bootstrap';

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'alamat' => 'nullable|string|max:255',
            'no_HP' => 'nullable|string|max:30',
            'gol_darah' => 'nullable|string|max:3',
            'tgl_lahir' => 'nullable|date',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'foto_upload' => 'nullable|image|max:2048', // 2MB Max
            'role' => 'required|in:jemaat,pengurus,pendeta',
        ];

        if (!$this->updatedata) {
            $rules['password'] = 'required|min:8';
            $rules['email'] .= '|unique:users,email';
        } else {
            $rules['email'] .= '|unique:users,email,' . $this->jemaat_id;
        }

        return $rules;
    }

    protected function messages()
    {
        return [
            'name.required' => 'Nama tidak boleh kosong',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 8 karakter',
            'foto_upload.image' => 'File harus berupa gambar',
            'foto_upload.max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
            'role.required' => 'Role harus diisi',
            'role.in' => 'Role harus salah satu dari jemaat, pengurus, atau pendeta',
        ];
    }

    public function showDetail($id)
    {
        $jemaat = User::findOrFail($id);
        $this->fillForm($jemaat);
        $this->updatedata = true;
        $this->jemaat_id = $id;
    }

    public function simpan()
    {
        $this->validate();

        $data = $this->prepareData();

        if (!$this->updatedata) {
            $data['password'] = Hash::make($this->password);
        }

        // Handle file upload
        if ($this->foto_upload) {
            $data['filename'] = $this->handleFileUpload($this->foto_upload, $this->name);
        }

        User::create($data);
        session()->flash('message', 'Data Jemaat berhasil disimpan.');
        $this->clear();
    }

    public function edit($id)
    {
        $jemaat = User::findOrFail($id);
        $this->fillForm($jemaat);
        $this->updatedata = true;
        $this->jemaat_id = $id;
    }

    public function update()
    {
        $this->validate();

        $jemaat = User::findOrFail($this->jemaat_id);

        // Handle file upload
        if ($this->foto_upload) {
            // Delete old image if exists
            if ($jemaat->filename && Storage::disk('public')->exists($jemaat->filename)) {
                Storage::disk('public')->delete($jemaat->filename);
            }
            $jemaat->filename = $this->handleFileUpload($this->foto_upload, $this->name);
        }

        // Update other fields
        $jemaat->update($this->prepareData());

        session()->flash('message', 'Data Jemaat berhasil diupdate.');
        $this->clear();
    }

    public function hapus()
    {
        $jemaat = User::findOrFail($this->jemaat_id);

        // Delete image from storage
        if ($jemaat->filename && Storage::disk('public')->exists($jemaat->filename)) {
            Storage::disk('public')->delete($jemaat->filename);
        }

        // Delete data from database
        $jemaat->delete();
        session()->flash('message', 'Data Jemaat berhasil dihapus.');
        $this->clear();
    }

    public function konfimasihapus($id)
    {
        $this->jemaat_id = $id;
    }

    public function sort($colomname)
    {
        $this->sortcolom = $colomname;
        $this->sortdirection = $this->sortdirection === 'asc' ? 'desc' : 'asc';
    }

    public function clear()
    {
        $this->reset([
            'name', 'email', 'password', 'alamat', 'no_HP', 'gol_darah',
            'role', 'filename', 'foto_upload', 'tgl_lahir', 'facebook',
            'instagram', 'cari', 'updatedata', 'jemaat_id'
        ]);
    }

    public function render()
    {
        $query = User::query();

        if ($this->cari) {
            $query->where('name', 'like', '%' . $this->cari . '%');
        }

        $members = $query->orderBy($this->sortcolom, $this->sortdirection)
                         ->paginate($this->cari ? 20 : 10);

        return view('livewire.data-jemaat', ['members' => $members]);
    }

    private function fillForm(User $jemaat)
    {
        $this->name = $jemaat->name;
        $this->email = $jemaat->email;
        $this->alamat = $jemaat->alamat;
        $this->no_HP = $jemaat->no_HP;
        $this->gol_darah = $jemaat->gol_darah;
        $this->filename = $jemaat->filename;
        $this->role = $jemaat->role;
        $this->tgl_lahir = $jemaat->tgl_lahir;
        $this->facebook = $jemaat->facebook;
        $this->instagram = $jemaat->instagram;
        $this->foto_upload = null;
    }

    private function prepareData()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'alamat' => $this->alamat,
            'no_HP' => $this->no_HP,
            'gol_darah' => $this->gol_darah,
            'tgl_lahir' => $this->tgl_lahir,
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'role' => $this->role,
        ];
    }

    private function handleFileUpload($file, $name)
    {
        $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $name);
        $extension = $file->getClientOriginalExtension();
        $filename = $cleanName . '.' . $extension;
        return $file->storeAs('foto-jemaat', $filename, 'public');
    }
}
