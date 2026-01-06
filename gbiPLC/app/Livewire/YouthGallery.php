<?php

namespace App\Livewire;

use App\Models\TblYouthGallery;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class YouthGallery extends Component
{

    use WithPagination, WithFileUploads;
    public $title, $description, $type, $file_path, $category, $event_date;
    protected $paginationTheme = 'bootstrap';
    public $updatedata = false;
    public $gallery_id;
    public $cari;
    public $sortcolom ='event_date';
    public $sortdirection = 'asc';
    
    public function show_detail($id)
    {
        $gallery = TblYouthGallery::where('tbl_youth_galleries.id', $id)
            ->first();

        if (!$gallery) {
            session()->flash('error', 'Data Event tidak ditemukan.');
            return;
        }

        $this->title = $gallery->title;
        $this->description = $gallery->description;
        $this->type = $gallery->type;
        $this->file_path = $gallery->file_path;
        $this->event_date = $gallery->event_date;
        
        $this->updatedata = true;
        $this->gallery_id = $id;
    }
    
    public function simpan()
    {
        $rules = [
            'title' => 'required',
            'type' => 'required',
            'event_date' => 'required|date',
            //'file_path' => 'nullable|max:1024', // 1MB Max
        ];
        $messages = [
            'title' => 'Judul tidak boleh kosong',
            'type' => 'Jenis file tidak boleh kosong',
            'event_date' => 'Tanggal tidak boleh kosong',
            //'file_path.image' => 'File harus berupa gambar',
            //'file_path.max' => 'Ukuran gambar tidak boleh lebih dari 1MB',
        ];
        $validated = $this->validate($rules, $messages);

        // Simpan data ke database
        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'event_date' => $this->event_date,
        ];

        // Cek apakah ada gambar yang diupload
        if ($this->file_path != null) {
            $data['file_path'] = $this->file_path->store('foto-youth_gallery', 'public');
        } else {
            $data['file_path'] = null; // atau bisa dihapus jika tidak ingin menyimpan key 'gambar' sama sekali
        }

        // Simpan ke database
        TblYouthGallery::create($data);
        session()->flash('message', 'Data Gallery berhasil disimpan.');
        $this->clear();
        // Initialization code can go here
    }

    public function edit($id)
    {
        $gallery = TblYouthGallery::where('tbl_youth_galleries.id', $id)
            ->first();

        if (!$gallery) {
            session()->flash('error', 'Data Gallery tidak ditemukan.');
            return;
        }

        $this->title = $gallery->title;
        $this->description = $gallery->description;
        $this->type = $gallery->type;
        $this->file_path = $gallery->file_path;
        $this->event_date = $gallery->event_date;
        $this->updatedata = true;
        $this->gallery_id = $id;
    }

    public function update()
    {
        $rules = [
            'title' => 'required',
            'type' => 'required',
            'event_date' => 'required|date',
            'file_path' => 'nullable|image|max:1024', // 1MB Max
        ];
        $messages = [
            'title' => 'Judul tidak boleh kosong',
            'type' => 'Jenis file tidak boleh kosong',
            'event_date' => 'Tanggal tidak boleh kosong',
            'file_path.image' => 'File harus berupa gambar',
            'file_path.max' => 'Ukuran gambar tidak boleh lebih dari 1MB',
        ];
        $validated = $this->validate($rules, $messages);


         // Cek apakah ada gambar yang diupload
        if ($this->file_path != null) {
            $data['file_path'] = $this->file_path->store('foto-youth_gallery', 'public');
        } else {
            $data['file_path'] = null; // atau bisa dihapus jika tidak ingin menyimpan key 'gambar' sama sekali
        }

        // Simpan ke database
        TblYouthGallery::where('id', $this->gallery_id)->update($data);
        session()->flash('message', 'Data Gallery berhasil diperbarui.');
        $this->clear();
    }

    public function clear()
    {
        $this->title = '';
        $this->description = '';
        $this->type = '';
        $this->file_path='';
        $this->updatedata = false;
        $this->gallery_id = '';
        $this->cari = '';

    }

    public function hapus()
    {

        $id = $this->gallery_id;
        $gallery = TblYouthGallery::find($id);
        if ($gallery) {
            // Hapus gambar dari storage
            $gambarPath = storage_path('app/public/' . $gallery->file_path);
            if (file_exists($gambarPath)) {
                unlink($gambarPath);
            }
            // Hapus data dari database
            $gallery->delete();
            session()->flash('message', 'Data Gallery berhasil dihapus.');
        } else {
            session()->flash('message', 'Data Gallery tidak ditemukan.');
        }

        $this->clear();
    }

    public function konfimasihapus($id)
    {

        $this->gallery_id = $id;
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
            $gallery = TblYouthGallery::where('title', 'like', '%' . $this->cari . '%')
            ->orderBy($this->sortcolom, $this->sortdirection)
            ->paginate(10);
        } else {
            $gallery = TblYouthGallery::orderBy($this->sortcolom,$this->sortdirection)
            ->paginate(10);
        }

        return view('livewire.youth-gallery',['gallery' => $gallery]);
    }

}
