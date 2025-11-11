<?php

namespace App\Livewire;

use App\Models\TblMateriKomsel;
use Livewire\Component;
use Livewire\WithPagination;

class MateriKomsel extends Component
{
    use WithPagination;

    public $tgl_komsel, $judul, $filename, $path;
    protected $paginationTheme = 'bootstrap';
    public $updatedata = false;
    public $materi_id;
    public $cari;
    public $sortcolom = 'tgl_komsel';
    public $sortdirection = 'asc';

    private function fillForm(TblMateriKomsel $materi)
    {
        /** @var TblMateriKomsel $materi */
        $this->tgl_komsel = $materi->tgl_komsel;
        $this->judul = $materi->judul;
        $this->path = $materi->path;
    }

    private function prepareData()
    {
        return [
            'tgl_komsel' => $this->tgl_komsel,
            'judul' => $this->judul,
            'path' => $this->path,
        ];
    }

    protected function rules()
    {
        $rules = [
            'tgl_komsel' => 'required|date',
            'judul' => 'required|string',
        ];
        return $rules;
    }

    protected function messages()
    {
        return [
            'tgl_komsel.required' => 'Tanggal tidak boleh kosong',
            'judul.required' => 'Judul tidak boleh kosong',
        ];
    }

    public function show_detail($id)
    {
        $materi = TblMateriKomsel::findOrFail($id);
        $this -> fillForm($materi);
        $this -> updatedata = true;
        $this -> materi_id = $id;
    }

    public function simpan()
    {
        $this->validate();

        $data = $this->prepareData();

        TblMateriKomsel::create($data);
        session()->flash('message', 'Data Materi Life Group berhasil disimpan.');
        $this->clear();
    }

    public function edit($id)
    {
        $materi = TblMateriKomsel::findOrFail($id);
        $this->fillForm($materi);
        $this->updatedata = true;
        $this->materi_id = $id;
    }

    public function update()
    {
        $this->validate();

        $materi = TblMateriKomsel::findOrFail($this->materi_id);

        // Update other fields
        $materi->update($this->prepareData());

        session()->flash('message', 'Data Materi Life Group berhasil diupdate.');
        $this->clear();
    }

    public function hapus()
    {
        $materi = TblMateriKomsel::findOrFail($this->materi_id);

        // Delete data from database
        $materi->delete();
        session()->flash('message', 'Data Materi Life Group berhasil dihapus.');
        $this->clear();
    }

    public function konfimasihapus($id)
    {
        $this->materi_id = $id;
    }

    public function sort($colomname)
    {
        $this->sortcolom = $colomname;
        $this->sortdirection = $this->sortdirection === 'asc' ? 'desc' : 'asc';
    }

    public function clear()
    {
        $this->reset([
            'tgl_komsel', 
            'judul', 
            'path',
        ]);
    }

    public function render()
    {
        $query = TblMateriKomsel::query();

        if ($this->cari) {
            $query->where('tgl_komsel', 'like', '%' . $this->cari . '%');
        }

        $materi = $query->orderBy($this->sortcolom, $this->sortdirection)
                         ->paginate($this->cari ? 20 : 10);
                         
        return view('livewire.materi-komsel',['materi' => $materi]);
    }
}
