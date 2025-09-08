<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TblEvent;
use Livewire\WithPagination;
use Livewire\WithFileUploads;


class EventTimMedia extends Component
{
    use WithPagination, WithFileUploads;
    public $tgl_event, $keterangan, $filename, $path;
    protected $paginationTheme = 'bootstrap';
    public $updatedata = false;
    public $event_id;
    public $cari;
    public $sortcolom ='tgl_event';
    public $sortdirection = 'asc';

    public function show_detail($id)
    {
        $event = TblEvent::where('tbl_events.id', $id)
            ->first();

        if (!$event) {
            session()->flash('error', 'Data Event tidak ditemukan.');
            return;
        }

        $this->tgl_event = $event->tgl_event;
        $this->keterangan = $event->keterangan;
        $this->filename = $event->filename;

        $this->updatedata = true;
        $this->event_id = $id;
    }

    public function simpan()
    {
        $rules = [
            'tgl_event' => 'required|date',
            'filename' => 'nullable|image|max:1024', // 1MB Max
        ];
        $messages = [
            'tgl_event' => 'Tanggal tidak boleh kosong',
            'filename.image' => 'File harus berupa gambar',
            'filename.max' => 'Ukuran gambar tidak boleh lebih dari 1MB',
        ];
        $validated = $this->validate($rules, $messages);

        // Simpan data ke database
        $data = [
            'tgl_event' => $this->tgl_event,
            'keterangan' => $this->keterangan,
        ];

        // Cek apakah ada gambar yang diupload
        if ($this->filename != null) {
            $data['filename'] = $this->filename->store('foto-event', 'public');
        } else {
            $data['filename'] = null; // atau bisa dihapus jika tidak ingin menyimpan key 'gambar' sama sekali
        }

        // Simpan ke database
        TblEvent::create($data);
        session()->flash('message', 'Data Event berhasil disimpan.');
        $this->clear();
        // Initialization code can go here
    }

    public function edit($id)
    {
        $event = TblEvent::where('tbl_events.id', $id)
            ->first();

        if (!$event) {
            session()->flash('error', 'Data Event tidak ditemukan.');
            return;
        }

        $this->tgl_event = $event->tgl_event;
        $this->keterangan = $event->keterangan;
        $this->filename = $event->filename;
        $this->updatedata = true;
        $this->event_id = $id;
    }

    public function update()
    {
        $rules = [
            'tgl_event' => 'required|date',
            'filename' => 'nullable|image|max:1024', // 1MB Max
        ];
        $messages = [
            'tgl_event' => 'Tanggal tidak boleh kosong',
            'filename.image' => 'File harus berupa gambar',
            'filename.max' => 'Ukuran gambar tidak boleh lebih dari 1MB',

        ];
        $validated = $this->validate($rules, $messages);


         // Cek apakah ada gambar yang diupload
        if ($this->filename != null) {
            $data['filename'] = $this->filename->store('foto-event', 'public');
        } else {
            $data['filename'] = null; // atau bisa dihapus jika tidak ingin menyimpan key 'gambar' sama sekali
        }

        // Simpan ke database
        TblEvent::where('id', $this->event_id)->update($data);
        session()->flash('message', 'Data Event berhasil diperbarui.');
        $this->clear();
    }

    public function clear()
    {
        $this->tgl_event = '';
        $this->keterangan = '';
        $this->filename = '';
        $this->updatedata = false;
        $this->event_id = '';
        $this->cari = '';


    }

    public function hapus()
    {

        $id = $this->event_id;
        $event = TblEvent::find($id);
        if ($event) {
            // Hapus gambar dari storage
            $gambarPath = storage_path('app/public/' . $event->filename);
            if (file_exists($gambarPath)) {
                unlink($gambarPath);
            }
            // Hapus data dari database
            $event->delete();
            session()->flash('message', 'Data Event berhasil dihapus.');
        } else {
            session()->flash('message', 'Data Event tidak ditemukan.');
        }

        $this->clear();
    }

    public function konfimasihapus($id)
    {

        $this->event_id = $id;
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
            $events = TblEvent::where('keterangan', 'like', '%' . $this->cari . '%')
            ->orderBy($this->sortcolom, $this->sortdirection)
            ->paginate(10);
        } else {
            $events = TblEvent::orderBy($this->sortcolom,$this->sortdirection)
            ->paginate(10);
        }

        return view('livewire.event-tim-media',['events' => $events]);
    }
}
