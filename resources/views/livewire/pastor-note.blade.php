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


    <!-- START FORM -->
    <div class="my-3 p-3 bg-body rounded ">        
        <form>
            <div class="row">
                <!-- Kolom Pertama -->
                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label for="tgl_note" class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" id="tgl_note" wire:model="tgl_note">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="note" class="col-sm-3 col-form-label">Pesan Gembala</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="note" wire:model="note"></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div>
                            <div class="form-group row">
                                <label for="filename" class="col-sm-3 col-form-label">Gambar</label>
                                <div class="col-sm-9">
                                    <input 
                                        type="file" 
                                        class="form-control @error('filename') is-invalid @enderror" 
                                        id="filename" 
                                        wire:model="filename" 
                                        accept=".jpg,.jpeg,.png"
                                        onchange="document.getElementById('label-gambar').innerText = this.files[0]?.name || 'Pilih gambar';"
                                    >
                                    <small id="label-gambar" class="form-text text-muted">Pilih Foto</small>
                                    @error('filename') 
                                        <span class="invalid-feedback d-block">{{ $message }}</span> 
                                    @enderror
                                    
                                    {{-- Preview Gambar --}}
                                    @isset($filename)
                                        @if ($filename instanceof \Illuminate\Http\UploadedFile)
                                            <div class="mt-3">
                                                <img src="{{ $filename->temporaryUrl() }}" alt="Preview Gambar" class="img-thumbnail object-contain rounded" style="max-height: 100px;">
                                            </div>
                                        @endif
                                    @endisset
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
                        <button type="button" class="btn btn-primary" name="submit" wire:click="simpan()">SIMPAN</button>
                    @else
                        <button type="button" class="btn btn-primary" name="submit" wire:click="update()">UPDATE</button>    
                    @endif
                    <button type="button" class="btn btn-secondary" name="submit" wire:click="clear()">Clear</button>    
                    
                </div>
            </div>
        </form>
    </div>
    
    <!-- AKHIR FORM -->

    <!-- START DATA -->
    
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <h4 class="font-medium">Data Suara Gembala</h4>
        <div class="pb-3 pt-3">
            <input type="text" class="form-control mb-3 w-25" placeholder="Search..." wire:model.live="cari">
        </div>
        {{ $pastornotes->links() }}
        <table class="table table-striped table-sortable ">
            <thead>
                <tr>
                    <th class="col-md-1">No</th>
                    <th class="col-md-2 sort" >Tanggal</th>
                    <th class="col-md-10 sort" >Pesan Gembala</th>
                    <th class="col-md-2 sort" >Foto</th>
                    <th class="col-md-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                
                
                @foreach ($pastornotes as $key => $value)
                <tr>
                    <td>{{ $pastornotes->firstItem() + $key }}</td>
                    <td>{{ $value->tgl_note  }}</td>
                    <td>{{ $value->note }}</td>
                    <td><img src="{{ asset('storage/' . $value->filename) }}" alt="Foto" class="p-0.5 object-contain rounded-full " 
                                style="width: 60px; height: 60px;"></td>
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





    
