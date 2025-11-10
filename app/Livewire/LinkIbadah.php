<?php

namespace App\Livewire;

use App\Models\TblIbadahRaya;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class LinkIbadah extends Component
{
    use WithPagination, WithFileUploads;

    public $tgl_ibadah, $ibadah_ke, $link_ibadah;
    protected $paginationTheme = 'bootstrap';
    public $updatedata = false;
    public $ibadah_id;
    public $cari;
    public $sortcolom = 'tgl_ibadah';
    public $sortdirection = 'asc';

    private function fillForm(TblIbadahRaya $ibadah)
    {
        $this->tgl_ibadah = $ibadah->tgl_ibadah;
        $this->ibadah_ke = $ibadah->ibadah_ke;
        $this->link_ibadah = $ibadah->link_ibadah;
    }

    private function prepareData()
    {
        return [
            'tgl_ibadah' => $this->tgl_ibadah,
            'ibadah_ke' => $this->ibadah_ke,
            'link_ibadah' => $this->link_ibadah,
        ];
    }

    protected function rules()
    {
        $rules = [
            'tgl_ibadah' => 'required|date',
        ];
        return $rules;
    }

    protected function messages()
    {
        return [
            'tgl_ibadah.required' => 'Tanggal tidak boleh kosong',
        ];
    }

    public function show_detail($id)
    {
        $ibadah = TblIbadahRaya::findOrFail($id);
        $this -> fillForm($ibadah);
        $this -> updatedata = true;
        $this -> ibadah_id = $id;
    }

    public function simpan()
    {
        $this->validate();

        $data = $this->prepareData();

        TblIbadahRaya::create($data);
        session()->flash('message', 'Data Ibadah Raya berhasil disimpan.');
        $this->clear();
    }

    public function edit($id)
    {
        $ibadah = TblIbadahRaya::findOrFail($id);
        $this->fillForm($ibadah);
        $this->updatedata = true;
        $this->ibadah_id = $id;
    }

    public function update()
    {
        $this->validate();

        $ibadah = TblIbadahRaya::findOrFail($this->jemaat_id);

        // Update other fields
        $ibadah->update($this->prepareData());

        session()->flash('message', 'Data Ibadah Raya berhasil diupdate.');
        $this->clear();
    }

    public function hapus()
    {
        $ibadah = TblIbadahRaya::findOrFail($this->ibadah_id);

        // Delete data from database
        $ibadah->delete();
        session()->flash('message', 'Data Ibadah Raya berhasil dihapus.');
        $this->clear();
    }

    public function konfimasihapus($id)
    {
        $this->ibadah_id = $id;
    }

    public function sort($colomname)
    {
        $this->sortcolom = $colomname;
        $this->sortdirection = $this->sortdirection === 'asc' ? 'desc' : 'asc';
    }

    public function clear()
    {
        $this->reset([
            'tgl_ibadah', 
            'ibadah_ke', 
            'link_ibadah',
        ]);
    }

    public function render()
    {
        $query = TblIbadahRaya::query();

        if ($this->cari) {
            $query->where('tgl_ibadah', 'like', '%' . $this->cari . '%');
        }

        $ibadah = $query->orderBy($this->sortcolom, $this->sortdirection)
                         ->paginate($this->cari ? 20 : 10);

        return view('livewire.link-ibadah', ['ibadah' => $ibadah]);
    }
}
