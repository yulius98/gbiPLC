<?php

namespace App\Livewire;

use App\Models\TblPastorNote;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class PastorNote extends Component
{
    use WithPagination, WithFileUploads;

    public $tgl_note, $note, $filename, $path;
    protected $paginationTheme = 'bootstrap';
    public $updatedata = false;
    public $note_id;
    public $cari;
    public $sortcolom ='tgl_note';
    public $sortdirection = 'asc';

    public function show_detail($id)
    {
        $pastornote = TblPastorNote::where('tbl_pastor_notes.id', $id)
            ->first();

        if (!$pastornote) {
            session()->flash('error', 'Data Pesan Gembala tidak ditemukan.');
            return;
        }

        $this->tgl_note = $pastornote->tgl_note;
        $this->note = $pastornote->note;
        $this->filename = $pastornote->filename;

        $this->updatedata = true;
        $this->note_id = $id;
    }

    public function simpan()
    {
        $rules = [
            'tgl_note' => 'required|date',
            'filename' => 'nullable|image|max:1024', // 1MB Max
        ];
        $messages = [
            'tgl_note' => 'Tanggal tidak boleh kosong',
            'filename.image' => 'File harus berupa gambar',
            'filename.max' => 'Ukuran gambar tidak boleh lebih dari 1MB',
        ];
        $validated = $this->validate($rules, $messages);

        // Simpan data ke database
        $data = [
            'tgl_note' => $this->tgl_note,
            'note' => $this->note,
        ];

        // Cek apakah ada gambar yang diupload
        if ($this->filename != null) {
            $data['filename'] = $this->filename->store('foto-pesangembala', 'public');
        } else {
            $data['filename'] = null; // atau bisa dihapus jika tidak ingin menyimpan key 'gambar' sama sekali
        }

        // Simpan ke database
        TblPastorNote::create($data);
        session()->flash('message', 'Data Pesan Gembala berhasil disimpan.');
        $this->clear();
        // Initialization code can go here
    }

    public function edit($id)
    {
        $pastornote = TblPastorNote::where('tbl_pastor_notes.id', $id)
            ->first();

        if (!$pastornote) {
            session()->flash('error', 'Data Pesan Gembala tidak ditemukan.');
            return;
        }

        $this->tgl_note = $pastornote->tgl_note;
        $this->note = $pastornote->note;
        $this->filename = $pastornote->filename;
        $this->updatedata = true;
        $this->note_id = $id;
    }

    public function update()
    {
        $rules = [
            'tgl_note' => 'required|date',
            'filename' => 'nullable|image|max:1024', // 1MB Max
        ];
        $messages = [
            'tgl_note' => 'Tanggal tidak boleh kosong',
            'filename.image' => 'File harus berupa gambar',
            'filename.max' => 'Ukuran gambar tidak boleh lebih dari 1MB',

        ];
        $validated = $this->validate($rules, $messages);

        // Cek apakah ada gambar yang diupload
        if ($this->filename != null) {
            $data['filename'] = $this->filename->store('foto-pesangembala', 'public');
        } else {
            $data['filename'] = null; // atau bisa dihapus jika tidak ingin menyimpan key 'gambar' sama sekali
        }

        // Simpan ke database
        TblPastorNote::where('id', $this->note_id)->update($data);
        session()->flash('message', 'Data Pesan Gembala berhasil diperbarui.');
        $this->clear();
    }

    public function clear()
    {
        $this->tgl_note = '';
        $this->note = '';
        $this->filename = '';
        $this->updatedata = false;
        $this->note_id = '';
        $this->cari = '';


    }

    public function hapus()
    {

        $id = $this->note_id;
        $pastornote = TblPastorNote::find($id);
        if ($pastornote) {
            // Hapus gambar dari storage
            $gambarPath = storage_path('app/public/' . $pastornote->filename);
            if (file_exists($gambarPath)) {
                unlink($gambarPath);
            }
            // Hapus data dari database
            $pastornote->delete();
            session()->flash('message', 'Data Jemaat berhasil dihapus.');
        } else {
            session()->flash('message', 'Data Jemaat tidak ditemukan.');
        }

        $this->clear();
    }

    public function konfimasihapus($id)
    {

        $this->note_id = $id;
    }

    public function sort($colomname){

        $this->sortcolom = $colomname;
        //dump($this->sortcolom);
        $this->sortdirection = $this->sortdirection == 'asc' ? 'desc' : 'asc';
        //dd($this->sortdirection);

    }


    public function render()
    {
        $pastornotes = TblPastorNote::where('tgl_note', 'like', '%' . $this->cari . '%')
            ->orWhere('note', 'like', '%' . $this->cari . '%')
            ->orderBy('tgl_note','desc')
            ->paginate(10);

        $dtpasstornote = TblPastorNote::orderBy('tgl_note', 'desc')
            ->first();

        return view('livewire.pastor-note', ['pastornotes' => $pastornotes,'dtpasstornote' => $dtpasstornote]);
    }
}
