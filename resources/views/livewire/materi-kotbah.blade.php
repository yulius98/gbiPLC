@php use Illuminate\Support\Facades\Storage; @endphp

<div class="container">
    @if ($errors->any())
        <div class="pt-3">
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    
    @if (session()->has('message'))
        <div class="pt-3">
            <div id="flash-message"  class="alert alert-success">
                {{ session('message') }}
            </div>
        </div>
    @endif
    
    @if (session()->has('error'))
        <div class="pt-3">
            <div id="flash-error" class="alert alert-danger">
                {{ session('error') }}
            </div>
        </div>
    @endif


    <!-- START FORM -->
    <div class="my-3 p-3 bg-body rounded ">
        <form>
            <div class="row">
                <!-- Kolom Pertama -->
                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label for="tgl_kotbah" class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" id="tgl_kotbah" wire:model="tgl_kotbah">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="judul" class="col-sm-3 col-form-label">Judul Kotbah</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="judul" wire:model="judul">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div>
                            <div class="form-group row">
                                <label for="filename" class="col-sm-3 col-form-label">File Kotbah</label>
                                <div class="col-sm-9">
                                    <!-- Chunked Upload untuk file besar (>50MB) -->
                                    <div id="chunked-upload-container" style="display: none;">
                                        <button type="button" class="btn btn-secondary" id="browseButton">
                                            <i class="fas fa-upload"></i> Pilih File (Upload Cepat)
                                        </button>
                                        <small class="form-text text-muted d-block">Untuk file besar (lebih dari 50MB)</small>
                                        
                                        <!-- Progress Bar -->
                                        <div id="upload-progress-container" style="display: none;" class="mt-3">
                                            <div class="progress">
                                                <div id="upload-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" 
                                                     role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                            </div>
                                            <small id="upload-status" class="text-muted d-block mt-2">Siap upload...</small>
                                        </div>
                                        
                                        <!-- Upload Success -->
                                        <div id="upload-success" style="display: none;" class="alert alert-success mt-3">
                                            <i class="fas fa-check-circle"></i> File berhasil diupload: <span id="uploaded-filename"></span>
                                        </div>
                                    </div>
                                    
                                    <!-- Upload biasa untuk file kecil -->
                                    <div id="normal-upload-container">
                                        <input
                                            type="file"
                                            class="form-control @error('filename') is-invalid @enderror"
                                            id="filename"
                                            wire:model="filename"
                                            accept=".pdf,.ppt,.pptx"
                                            onchange="handleFileSelect(this);"
                                        >
                                        <small id="label-kotbah" class="form-text text-muted">File kecil (< 50MB) menggunakan upload biasa</small>
                                        <small class="form-text text-info d-block">Untuk file > 50MB, gunakan tombol "Upload Cepat"</small>
                                        
                                        <!-- Loading Indicator -->
                                        <div wire:loading wire:target="filename" class="text-info mt-2">
                                            <i class="fas fa-spinner fa-spin"></i> Mengupload file...
                                        </div>
                                        
                                        @error('filename')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <!-- Toggle button -->
                                    <button type="button" class="btn btn-link btn-sm mt-2" onclick="toggleUploadMethod()">
                                        <span id="toggle-text">Gunakan Upload Cepat</span>
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol SIMPAN -->
            <div class="mb-3 row">
                <div class="col-12 text-end">
                    @if ($updatedata == false)
                        <button 
                            type="button" 
                            class="btn btn-primary" 
                            name="submit" 
                            wire:click="simpan()"
                            wire:loading.attr="disabled"
                            wire:target="simpan, filename">
                            <span wire:loading.remove wire:target="simpan">SIMPAN</span>
                            <span wire:loading wire:target="simpan">
                                <i class="fas fa-spinner fa-spin"></i> Menyimpan...
                            </span>
                        </button>
                    @else
                        <button 
                            type="button" 
                            class="btn btn-primary" 
                            name="submit" 
                            wire:click="update()"
                            wire:loading.attr="disabled"
                            wire:target="update, filename">
                            <span wire:loading.remove wire:target="update">UPDATE</span>
                            <span wire:loading wire:target="update">
                                <i class="fas fa-spinner fa-spin"></i> Memperbarui...
                            </span>
                        </button>
                    @endif
                    <button type="button" class="btn btn-secondary" name="submit" wire:click="clear()">Clear</button>

                </div>
            </div>
        </form>
    </div>

    <!-- AKHIR FORM -->

    <!-- START DATA -->

    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <h4 class="font-medium">Data Materi Kotbah</h4>
        <div class="pb-3 pt-3">
            <input type="text" class="form-control mb-3 w-25" placeholder="Search..." wire:model.live="cari">
        </div>
        {{ $materikotbah->links() }}
        <table class="table table-striped table-sortable ">
            <thead>
                <tr>
                    <th class="col-md-1">No</th>
                    <th class="col-md-2 sort" >Tanggal</th>
                    <th class="col-md-10 sort" >Judul Kotbah</th>
                    <th class="col-md-2 sort" >File</th>
                    <th class="col-md-2">Aksi</th>
                </tr>
            </thead>
            <tbody>


                @foreach ($materikotbah as $key => $value)
                <tr>
                    <td>{{ $materikotbah->firstItem() + $key }}</td>
                    <td>{{ $value->tgl_kotbah  }}</td>
                    <td>{{ $value->judul }}</td>
                    <!--<td><img src="{{ asset('storage/' . $value->filename) }}" alt="File" class="p-0.5 object-contain rounded-full "
                                style="width: 60px; height: 60px;">
                    </td> -->

                    <td data-label ="File Kotbah">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M4 2h10l6 6v14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z" fill="#f44336"/>
                            <path d="M14 2v6h6" fill="#c62828"/>
                            <text x="6" y="17" font-size="6" fill="white" font-family="Arial, sans-serif" font-weight="bold">PDF/PPT</text>
                        </svg>
                    </td>

                    <td>
                        <div class="d-flex gap-1">
                            <a wire:click="show_detail({{ $value->id }})" class="btn btn-warning btn-sm">Detail</a>
                            <a wire:click="edit({{ $value->id }})" class="btn btn-warning btn-sm">Edit</a>
                            <a wire:click="konfimasihapus({{ $value->id }})" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Del</a>
                        </div>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>

    </div>
    <!-- AKHIR DATA -->
    <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Hapus Data</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                <button type="button" class="btn btn-primary" wire:click="hapus()" data-bs-dismiss="modal">YA</button>
            </div>
            </div>
        </div>
    </div>
</div>






