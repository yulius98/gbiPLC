<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TblPopupAds;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class PopUpAds extends Component
{
    use WithPagination, WithFileUploads;
    public $filename, $path;
    public $name;
    protected $paginationTheme = 'bootstrap';
    public $updatedata = false;
    public $popupads_id;
    public $cari;

    public function show_detail($id)
    {

        $popup = TblPopupAds::where('tbl_popup_ads.id', $id)
            ->first();

            $this->filename = $popup->filename;
            $this->updatedata = true;
            $this->popupads_id = $id;

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
            $data['filename'] = $this->filename->store('foto-popupads', 'public');
        } else {
            $data['foto-popupads'] = null; // atau bisa dihapus jika tidak ingin menyimpan key 'gambar' sama sekali
        }



        // Simpan ke database
        TblPopupAds::create($data);
        session()->flash('message', 'Data Ads berhasil disimpan.');
        $this->clear();
        // Initialization code can go here
    }

    public function edit($id)
    {
        $popup = TblPopupAds::where('tbl_popup_ads.id', $id)
            ->first();
        //dd($barang);

        $this-> filename = $popup-> filename;
        $this->updatedata = true;
        $this->popupads_id = $id;
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

        $popup = TblPopupAds::find($this->popupads_id);

        // Simpan gambar jika ada upload baru
        if($popup->filename != null) {
            $gambarPath = storage_path('app/public/' . $popup->filename);
            if (file_exists($gambarPath)) {
                unlink($gambarPath);
            }
        }

         // Cek apakah ada gambar yang diupload
        if ($this->filename != null) {
            $data['filename'] = $this->filename->store('foto-popupads', 'public');
        } else {
            $data['foto-popupads'] = null; // atau bisa dihapus jika tidak ingin menyimpan key 'gambar' sama sekali
        }

        // Update field lainnya
        $popup-> save();

        session()->flash('message', 'Data Ads berhasil diupdate.');
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

        $id = $this->popupads_id;
        $popup = TblPopupAds::find($id);
        if ($popup) {

            // Hapus gambar dari storage
            $gambarPath = storage_path('app/public/' . $popup->filename);
            if (file_exists($gambarPath)) {
                unlink($gambarPath);
            }
            // Hapus data dari database
            $popup->delete();
            session()->flash('message', 'Data Ads berhasil dihapus.');
        } else {
            session()->flash('message', 'Data Ads tidak ditemukan.');
        }

        $this->clear();
    }

    public function konfimasihapus($id)
    {

        $this->popupads_id = $id;
        
    }

    public function render()
    {
        $popupAds = TblPopupAds::paginate(5);

        return view('livewire.pop-up-ads',['popupAds' => $popupAds]);
    }
}
