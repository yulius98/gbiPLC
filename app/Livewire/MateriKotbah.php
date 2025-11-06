<?php

namespace App\Livewire;

use App\Models\TblMateriKotbah;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class MateriKotbah extends Component
{
    use WithPagination, WithFileUploads; 

    public $tgl_kotbah, $judul, $filename, $path;
    public $uploadedFilePath; // Untuk menyimpan path file yang sudah diupload via chunk
    public $uploadProgress = 0; // Progress upload (0-100)
    public $isUploading = false; // Status sedang upload atau tidak
    protected $paginationTheme = 'bootstrap';
    public $updatedata = false;
    public $kotbah_id;
    public $cari; 
    public $sortcolom ='tgl_kotbah'; 
    public $sortdirection = 'asc';

    protected $listeners = ['fileUploaded' => 'handleFileUploaded'];

    public function handleFileUploaded($filePath)
    {
        $this->uploadedFilePath = $filePath;
        $this->isUploading = false;
        $this->uploadProgress = 100;
    }

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
        ];
        $messages = [
            'tgl_kotbah.required' => 'Tanggal tidak boleh kosong',
        ];
        
        try {
            $validated = $this->validate($rules, $messages);
            
            // Simpan data ke database
            $data = [
                'tgl_kotbah' => $this->tgl_kotbah,
                'judul' => $this->judul,
            ];

            // Cek apakah ada file yang diupload via chunk upload
            if ($this->uploadedFilePath) {
                $data['filename'] = $this->uploadedFilePath;
                Log::info('Using chunked upload file', ['path' => $this->uploadedFilePath]);
            }
            // Fallback ke upload biasa jika ada
            elseif ($this->filename && is_object($this->filename)) {
                Log::info('Attempting to upload file', [
                    'original_name' => $this->filename->getClientOriginalName(),
                    'mime_type' => $this->filename->getMimeType(),
                    'size' => $this->filename->getSize()
                ]);
                
                $filePath = $this->filename->store('materi-kotbah', 'public');
                
                if ($filePath) {
                    $data['filename'] = $filePath;
                    Log::info('File uploaded successfully', ['path' => $filePath]);
                } else {
                    Log::error('File upload returned false');
                    session()->flash('error', 'Gagal menyimpan file.');
                    return;
                }
            }

            // Simpan ke database
            $saved = TblMateriKotbah::create($data);
            Log::info('Data saved to database', ['id' => $saved->id]);
            
            session()->flash('message', 'Data Materi Kotbah berhasil disimpan.');
            $this->clear();
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw validation exceptions so they display properly
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error saving materi kotbah', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
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
        ];
        $messages = [
            'tgl_kotbah.required' => 'Tanggal tidak boleh kosong',
        ];
        $validated = $this->validate($rules, $messages);
        
        // Siapkan data untuk update
        $data = [
            'tgl_kotbah' => $this->tgl_kotbah,
            'judul' => $this->judul,
        ];

        // Cek apakah ada file yang diupload via chunk upload
        if ($this->uploadedFilePath) {
            // Hapus file lama jika ada
            $materikotbah = TblMateriKotbah::find($this->kotbah_id);
            if ($materikotbah && $materikotbah->filename) {
                $oldFilePath = storage_path('app/public/' . $materikotbah->filename);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            
            $data['filename'] = $this->uploadedFilePath;
        }
        // Fallback ke upload biasa jika ada
        elseif ($this->filename && is_object($this->filename)) {
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
        $this->filename = null;
        $this->uploadedFilePath = null;
        $this->uploadProgress = 0;
        $this->isUploading = false;
        $this->updatedata = false;
        $this->kotbah_id = '';
        $this->cari = '';
        $this->resetValidation();
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
