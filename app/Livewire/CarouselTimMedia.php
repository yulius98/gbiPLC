<?php

namespace App\Livewire;

use App\Models\TblCarousel;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CarouselTimMedia extends Component
{
    use WithPagination, WithFileUploads;
    public $filename, $path; 
    public $name;
    protected $paginationTheme = 'bootstrap';
    public $updatedata = false;
    public $caraousel_id;
    public $cari; 
    

    public function show_detail($id)
    {
        
        $carousel = TblCarousel::where('tbl_carousels.id', $id)
            ->first();
        
            $this->filename = $carousel->filename;
            $this->updatedata = true;
            $this->caraousel_id = $id;
       
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
        
       
         // Cek apakah ada gambar yang diupload
        if ($this->filename != null) {
            $data['filename'] = $this->filename->store('foto-carousel', 'public');
        } else {
            $data['foto-carousel'] = null; // atau bisa dihapus jika tidak ingin menyimpan key 'gambar' sama sekali
        }



        // Simpan ke database
        TblCarousel::create($data);
        session()->flash('message', 'Data Carausel berhasil disimpan.');
        $this->clear();
        // Initialization code can go here
    }

    public function edit($id)
    {    
        $carousel = TblCarousel::where('tbl_carousels.id', $id)
            ->first();
        //dd($barang);

        $this-> filename = $carousel-> filename;     
        $this->updatedata = true;
        $this->caraousel_id = $id;
    }

    public function update()
    {
        $rules = [
            'filename' => 'nullable|image|max:2048', // 1MB Max
            
        ];
        $messages = [
            
            'filename.image' => 'File harus berupa gambar',
            'filename.max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
            
        ];
        $validated = $this->validate($rules, $messages);
        
        $carousel = TblCarousel::find($this->caraousel_id);

        // Simpan gambar jika ada upload baru
        if($carousel->filename != null) {
            $gambarPath = storage_path('app/public/' . $carousel->filename);
            if (file_exists($gambarPath)) {
                unlink($gambarPath);
            }
        }

         // Cek apakah ada gambar yang diupload
        if ($this->filename != null) {
            $data['filename'] = $this->filename->store('foto-carousel', 'public');
        } else {
            $data['foto-carousel'] = null; // atau bisa dihapus jika tidak ingin menyimpan key 'gambar' sama sekali
        }

        // Update field lainnya
        $carousel-> save();

        session()->flash('message', 'Data Carousel berhasil diupdate.');
        $this->clear();
    }

    public function clear()
    {
        
        $this-> filename = '';
        $this->cari = '';
        
        $this->updatedata = false;
    }

    public function hapus()
    {
        
        $id = $this->caraousel_id;
        $carousel = TblCarousel::find($id);
        if ($carousel) {
            
            // Hapus gambar dari storage
            $gambarPath = storage_path('app/public/' . $carousel->filename);
            if (file_exists($gambarPath)) {
                unlink($gambarPath);
            }
            // Hapus data dari database
            $carousel->delete();
            session()->flash('message', 'Data Carousel berhasil dihapus.');
        } else {
            session()->flash('message', 'Data Carousel tidak ditemukan.');
        }

        $this->clear();
    }

    public function konfimasihapus($id)
    {
        
        $this->caraousel_id = $id;
    }
    public function render()
    {
        $dtcarousel = TblCarousel::paginate(5);

        return view('livewire.carousel-tim-media',['dtcarousel' => $dtcarousel]);
    }
}