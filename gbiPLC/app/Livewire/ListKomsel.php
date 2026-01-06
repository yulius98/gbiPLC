<?php

namespace App\Livewire;

use App\Models\TblKomsel;
use Livewire\Component;
use Livewire\WithPagination;

class ListKomsel extends Component
{
    use WithPagination;

    public $nama_komsel, $ketua_komsel, $no_telp, $alamat;
    protected $paginationTheme = 'bootstrap';
    public $updatedata = false;
    public $komsel_id;
    public $cari;
    public $sortcolom = 'nama_komsel';
    public $sortdirection = 'asc';
    
    private function fillForm(TblKomsel $komsel)
    {
        $this->nama_komsel = $komsel->nama_komsel;
        $this->ketua_komsel = $komsel->ketua_komsel;
        $this->no_telp = $komsel->no_telp;
        $this->alamat = $komsel->alamat;
    }

    private function prepareData()
    {
        return [
            'nama_komsel' => $this->nama_komsel,
            'ketua_komsel' => $this->ketua_komsel,
            'no_telp' => $this->no_telp,
            'alamat' => $this->alamat,
        ];
    }

    protected function rules()
    {
        $rules = [
            'nama_komsel' => 'required|string|max:255',
            'ketua_komsel' => 'required|string|max:255',
        ];
        return $rules;
    }

    protected function messages()
    {
        return [
            'nama_komsel' => 'Nama Komsel tidak boleh kosong',
            'ketua_komsel' => 'Nama Ketua Komsel tidak boleh kosong',
        ];
    }

    public function show_detail($id)
    {
        $komsel = TblKomsel::findOrFail($id);
        $this -> fillForm($komsel);
        $this -> updatedata = true;
        $this -> komsel_id = $id;
    }

    public function simpan()
    {
        $this->validate();

        $data = $this->prepareData();

        TblKomsel::create($data);
        session()->flash('message', 'Data Komsel berhasil disimpan.');
        $this->clear();
    }

    public function edit($id)
    {
        $komsel = TblKomsel::findOrFail($id);
        $this->fillForm($komsel);
        $this->updatedata = true;
        $this->komsel_id = $id;
    }

    public function update()
    {
        $this->validate();

        $komsel = TblKomsel::findOrFail($this->komsel_id);

        // Update other fields
        $komsel->update($this->prepareData());

        session()->flash('message', 'Data Komsel berhasil diupdate.');
        $this->clear();
    }

    public function hapus()
    {
        $komsel = TblKomsel::findOrFail($this->komsel_id);

        // Delete data from database
        $komsel->delete();
        session()->flash('message', 'Data Komsel berhasil dihapus.');
        $this->clear();
    }

    public function konfimasihapus($id)
    {
        $this->komsel_id = $id;
    }

    public function sort($colomname)
    {
        $this->sortcolom = $colomname;
        $this->sortdirection = $this->sortdirection === 'asc' ? 'desc' : 'asc';
    }

    public function clear()
    {
        $this->reset([
            'nama_komsel',
            'ketua_komsel',
            'no_telp',
            'alamat',
        ]);
    }

    public function render()
    {
        $query = TblKomsel::query();

        if ($this->cari) {
            $query->where('nama_komsel', 'like', '%' . $this->cari . '%');
        }

        $komsel = $query->orderBy($this->sortcolom, $this->sortdirection)
                         ->paginate($this->cari ? 20 : 10);

        return view('livewire.list-komsel', ['komsel' => $komsel]);
    }
}
