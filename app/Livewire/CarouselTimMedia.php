<?php

namespace App\Livewire;

use App\Models\TblCarousel;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class CarouselTimMedia extends Component
{
    use WithPagination, WithFileUploads;

    // Form properties
    public $filename;
    public $imageUpload;

    // Component state
    public $updatedata = false;
    public $carousel_id;
    public $cari = '';

    protected $paginationTheme = 'bootstrap';

    protected function rules()
    {
        return [
            'imageUpload' => 'required|image|max:2048', // 2MB Max
        ];
    }

    protected function messages()
    {
        return [
            'imageUpload.required' => 'Gambar harus diupload',
            'imageUpload.image' => 'File harus berupa gambar',
            'imageUpload.max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
        ];
    }

    public function showDetail($id)
    {
        $carousel = TblCarousel::findOrFail($id);
        $this->filename = $carousel->filename;
        $this->updatedata = true;
        $this->carousel_id = $id;
    }

    public function simpan()
    {
        $this->validate();

        $data = [];
        if ($this->imageUpload) {
            $data['filename'] = $this->imageUpload->store('foto-carousel', 'public');
        }

        TblCarousel::create($data);
        session()->flash('message', 'Data Carousel berhasil disimpan.');
        $this->clear();
    }

    public function edit($id)
    {
        $carousel = TblCarousel::findOrFail($id);
        $this->filename = $carousel->filename;
        $this->updatedata = true;
        $this->carousel_id = $id;
    }

    public function update()
    {
        $this->validate();

        $carousel = TblCarousel::findOrFail($this->carousel_id);

        // Delete old image if exists and new image is uploaded
        if ($this->imageUpload) {
            if ($carousel->filename && Storage::disk('public')->exists($carousel->filename)) {
                Storage::disk('public')->delete($carousel->filename);
            }
            $carousel->filename = $this->imageUpload->store('foto-carousel', 'public');
            $carousel->save();
        }

        session()->flash('message', 'Data Carousel berhasil diupdate.');
        $this->clear();
    }

    public function hapus()
    {
        $carousel = TblCarousel::findOrFail($this->carousel_id);

        // Delete image from storage
        if ($carousel->filename && Storage::disk('public')->exists($carousel->filename)) {
            Storage::disk('public')->delete($carousel->filename);
        }

        // Delete data from database
        $carousel->delete();
        session()->flash('message', 'Data Carousel berhasil dihapus.');
        $this->clear();
    }

    public function konfimasihapus($id)
    {
        $this->carousel_id = $id;
    }

    public function clear()
    {
        $this->reset(['filename', 'imageUpload', 'cari', 'updatedata', 'carousel_id']);
    }

    public function render()
    {
        $carousels = TblCarousel::paginate(5);
        return view('livewire.carousel-tim-media', ['carousels' => $carousels]);
    }
}
