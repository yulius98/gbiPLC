<?php

namespace App\Livewire;

use App\Models\TblMateriKotbah;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Component;

class MateriKotbah extends Component
{
    use WithPagination, WithFileUploads; 

    public $tgl_kotbah, $judul, $filename, $path;
    protected $paginationTheme = 'bootstrap';
    public $updatedata = false;
    public $kotbah_id;
    public $cari; 
    public $sortcolom ='tgl_kotbah'; 
    public $sortdirection = 'asc';

    public function show_detail($id)
    {
        $materikotbah = TblMateriKotbah::where('tbl_materi_kotbahs.id', $id)
            ->first();

        if (!$materikotbah) {
            session()->flash('error', 'Data Materi Kotbah tidak ditemukan.');
            return;
        }

        $this->tgl_kotbah = $materikotbah->tgl_kotbah;
        $this->judul = $materikotbah->judul;
        $this->filename = $materikotbah->filename;

        $this->updatedata = true;
        $this->kotbah_id = $id;

    }

    public function simpan()
    {
        $rules = [
            'tgl_kotbah' => 'required|date',
            'filename' => 'nullable|file|mimes:pdf,ppt,pptx|max:10240', // 10MB Max untuk file PDF/PowerPoint
        ];
        $messages = [
            'tgl_kotbah.required' => 'Tanggal tidak boleh kosong',
            'filename.file' => 'File harus berupa file yang valid',
            'filename.mimes' => 'File harus berupa PDF atau PowerPoint (ppt, pptx)',
            'filename.max' => 'Ukuran file tidak boleh lebih dari 10MB',
        ];
        $validated = $this->validate($rules, $messages);

        // Simpan data ke database
        $data = [
            'tgl_kotbah' => $this->tgl_kotbah,
            'judul' => $this->judul,
        ];

        // Cek apakah ada file yang diupload
        if ($this->filename != null) {
            $data['filename'] = $this->filename->store('materi-kotbah', 'public');
        }

        // Simpan ke database
        TblMateriKotbah::create($data);
        session()->flash('message', 'Data Materi Kotbah berhasil disimpan.');
        $this->clear();
        // Initialization code can go here
    }

    public function edit($id)
    {    
        $materikotbah = TblMateriKotbah::where('tbl_materi_kotbahs.id', $id)
            ->first();

        if (!$materikotbah) {
            session()->flash('error', 'Data Materi Kotbah tidak ditemukan.');
            return;
        }

        $this->tgl_kotbah = $materikotbah->tgl_kotbah;
        $this->judul = $materikotbah->judul;
        $this->filename = $materikotbah->filename;
        $this->updatedata = true;
        $this->kotbah_id = $id;
    }

    public function update()
    {
        $rules = [
            'tgl_kotbah' => 'required|date',
            'filename' => 'nullable|file|mimes:pdf,ppt,pptx|max:10240', // 10MB Max untuk file PDF/PowerPoint
        ];
        $messages = [
            'tgl_kotbah.required' => 'Tanggal tidak boleh kosong',
            'filename.file' => 'File harus berupa file yang valid',
            'filename.mimes' => 'File harus berupa PDF atau PowerPoint (ppt, pptx)',
            'filename.max' => 'Ukuran file tidak boleh lebih dari 10MB',
        ];
        $validated = $this->validate($rules, $messages);
        
        // Siapkan data untuk update
        $data = [
            'tgl_kotbah' => $this->tgl_kotbah,
            'judul' => $this->judul,
        ];

        // Cek apakah ada file yang diupload
        if ($this->filename && is_object($this->filename)) {
            // Hapus file lama jika ada
            $materikotbah = TblMateriKotbah::find($this->kotbah_id);
            if ($materikotbah && $materikotbah->filename) {
                $oldFilePath = storage_path('app/public/' . $materikotbah->filename);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            
            // Simpan file baru
            $data['filename'] = $this->filename->store('materi-kotbah', 'public');
        }

        // Update ke database
        TblMateriKotbah::where('id', $this->kotbah_id)->update($data);
        session()->flash('message', 'Data Materi Kotbah berhasil diperbarui.');
        $this->clear();
    }

    public function clear()
    {
        $this->tgl_kotbah = '';
        $this->judul = '';
        $this->filename = '';
        $this->updatedata = false;
        $this->kotbah_id = '';
        $this->cari = '';   
    }

    public function hapus()
    {
        
        $id = $this->kotbah_id;
        $materikotbah = TblMateriKotbah::find($id);
        if ($materikotbah) {
            // Hapus file dari storage jika ada
            if ($materikotbah->filename) {
                $filePath = storage_path('app/public/' . $materikotbah->filename);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            // Hapus data dari database
            $materikotbah->delete();
            session()->flash('message', 'Data Materi Kotbah berhasil dihapus.');
        } else {
            session()->flash('message', 'Data Materi Kotbah tidak ditemukan.');
        }

        $this->clear();
    }

    public function konfimasihapus($id)
    {   
        $this->kotbah_id = $id;
    }

    public function sort($colomname)
    {    
        $this->sortcolom = $colomname;
        //dump($this->sortcolom);
        $this->sortdirection = $this->sortdirection == 'asc' ? 'desc' : 'asc';
        //dd($this->sortdirection);   
    }


    public function render()
    {
        $materikotbah = TblMateriKotbah::where('tgl_kotbah', 'like', '%' . $this->cari . '%')
            ->orWhere('judul', 'like', '%' . $this->cari . '%')
            ->orderBy($this->sortcolom, $this->sortdirection)
            ->paginate(10);

        $dtmaterikotbah = TblMateriKotbah::orderBy('tgl_kotbah', 'desc')
            ->first();

        return view('livewire.materi-kotbah', ['materikotbah' => $materikotbah,'dtmaterikotbah' => $dtmaterikotbah]);
    }
}
