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
    public $tema;
    public $description;
    public $filename; // For file upload
    public $oldFilename; // For storing existing file path

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
        $this->tema = $carousel->tema;
        $this->description = $carousel->description;
        $this->filename = $carousel->filename;
        $this->updatedata = true;
        $this->carousel_id = $id;
    }

    public function simpan()
    {
        $rules = [
            'filename' => 'nullable|image|max:1024', // 1MB Max
        ];
        $messages = [
            'filename.image' => 'File harus berupa gambar',
            'filename.max' => 'Ukuran gambar tidak boleh lebih dari 1MB',
        ];
        $validated = $this->validate($rules, $messages);

        $data = [
            'tema' => $this->tema,
            'description' => $this->description,
        ];
        if ($this->filename != null) {
            $data['filename'] = $this->filename->store('foto-carousel', 'public');
        } else {
            $data['filename'] = null;
        }

        TblCarousel::create($data);
        session()->flash('message', 'Data Carousel berhasil disimpan.');
        $this->clear();
    }

    public function edit($id)
    {
        $carousel = TblCarousel::findOrFail($id);
        $this->tema = $carousel->tema;
        $this->description = $carousel->description;
        $this->oldFilename = $carousel->filename; // Store old filename separately
        $this->filename = null; // Reset file upload
        $this->updatedata = true;
        $this->carousel_id = $id;
    }

    public function update()
    {
        $rules = [
            'filename' => 'nullable|image|max:1024', // 1MB Max
        ];
        $messages = [
            'filename.image' => 'File harus berupa gambar',
            'filename.max' => 'Ukuran gambar tidak boleh lebih dari 1MB',
        ];
        $validated = $this->validate($rules, $messages);

        $carousel = TblCarousel::findOrFail($this->carousel_id);

        $carousel->tema = $this->tema;
        $carousel->description = $this->description;

        // Delete old image if exists and new image is uploaded
        if ($this->filename != null) {
            if ($carousel->filename && Storage::disk('public')->exists($carousel->filename)) {
                Storage::disk('public')->delete($carousel->filename);
            }
            $carousel->filename = $this->filename->store('foto-carousel', 'public');
        }

        $carousel->save();

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
        $this->reset(['filename', 'oldFilename', 'tema', 'description', 'cari', 'updatedata', 'carousel_id']);
    }

    public function render()
    {
        $carousels = TblCarousel::paginate(5);
        return view('livewire.carousel-tim-media', ['carousels' => $carousels]);
    }
}
