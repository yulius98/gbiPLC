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
                    <div>
                        <div class="mb-3 row">
                            <label for="tgl_event" class="col-sm-3 col-form-label">Tanggal event</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="tgl_event" wire:model="tgl_event">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="Keterangan" class="col-sm-3 col-form-label">Keterangan Event</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="keterangan" wire:model="keterangan"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="filename" class="col-sm-3 col-form-label">Foto Event</label>
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
                                        @php
                                            try {
                                                // Method 1: Try temporary URL (server-side)
                                                $tempUrl = $filename->temporaryUrl();

                                                // Method 2: Generate data URI for fallback (client-side independent)
                                                $imageData = base64_encode(file_get_contents($filename->getRealPath()));
                                                $mimeType = $filename->getMimeType();
                                                $dataUri = "data:{$mimeType};base64,{$imageData}";

                                                // Log ke Laravel log
                                                \Log::info('Event - Temporary URL Generated', [
                                                    'url' => $tempUrl,
                                                    'filename' => $filename->getClientOriginalName(),
                                                    'size' => $filename->getSize(),
                                                    'mime' => $filename->getMimeType(),
                                                    'path' => $filename->getRealPath(),
                                                    'dataUri_length' => strlen($dataUri)
                                                ]);
                                            } catch (\Exception $e) {
                                                \Log::error('Event - Failed to generate preview', [
                                                    'error' => $e->getMessage(),
                                                    'filename' => $filename->getClientOriginalName()
                                                ]);
                                                $tempUrl = null;
                                                $dataUri = null;
                                            }
                                        @endphp

                                        @if($dataUri)
                                            <div class="mt-3 border p-2 rounded">
                                                {{-- Use Data URI for immediate preview (always works) --}}
                                                <img src="{{ $dataUri }}"
                                                     id="preview-img-event-{{ md5($filename->getClientOriginalName()) }}"
                                                     alt="Preview Gambar"
                                                     class="img-thumbnail object-contain rounded d-block mb-2"
                                                     style="max-height: 150px;"
                                                     onload="document.getElementById('img-success-event-{{ md5($filename->getClientOriginalName()) }}').style.display='block';">

                                                <!--
                                                <div id="img-success-event-{{ md5($filename->getClientOriginalName()) }}" class="alert alert-success mb-2" style="display:none;">
                                                    <small>✓ Preview berhasil dimuat</small>
                                                </div>

                                                <small class="d-block text-muted">
                                                    <strong>File:</strong> {{ $filename->getClientOriginalName() }}<br>
                                                    <strong>Size:</strong> {{ number_format($filename->getSize() / 1024, 2) }} KB<br>
                                                    <strong>Type:</strong> {{ $filename->getMimeType() }}
                                                </small>
                                                -->
                                                {{--
                                                @if($tempUrl)
                                                <details class="mt-2">
                                                    <summary class="text-muted" style="cursor: pointer; font-size: 0.8rem;">Debug Info</summary>
                                                    <small class="d-block text-muted mt-1" style="font-size: 0.7rem; word-break: break-all;">
                                                        <strong>Temp URL:</strong> {{ $tempUrl }}<br>
                                                        <strong>Preview Method:</strong> Data URI (Base64)
                                                    </small>
                                                </details>
                                                @endif
                                                --}}
                                            </div>

                                            <script>
                                                (function() {
                                                    console.log('=== Event - Image Preview ===');
                                                    console.log('File name:', '{{ $filename->getClientOriginalName() }}');
                                                    console.log('File size:', '{{ number_format($filename->getSize() / 1024, 2) }}', 'KB');
                                                    console.log('Mime type:', '{{ $filename->getMimeType() }}');
                                                    console.log('Preview method:', 'Data URI (Base64)');

                                                    @if($tempUrl)
                                                    console.log('Temporary URL (for reference):', '{{ $tempUrl }}');

                                                    // Test temporary URL in background
                                                    fetch('{{ $tempUrl }}', {
                                                        method: 'GET',
                                                        credentials: 'include'
                                                    })
                                                    .then(response => {
                                                        console.log('Temp URL Status:', response.status, response.ok ? '✓ OK' : '✗ FAILED');
                                                        if (!response.ok) {
                                                            return response.text().then(text => {
                                                                console.error('Temp URL Error:', text.substring(0, 200));
                                                            });
                                                        }
                                                    })
                                                    .catch(error => {
                                                        console.error('Temp URL Fetch Error:', error.message);
                                                    });
                                                    @endif
                                                })();
                                            </script>
                                        @else
                                            <div class="mt-3 alert alert-info">
                                                <small>File dipilih: {{ $filename->getClientOriginalName() }} (Preview tidak tersedia)</small>
                                            </div>
                                        @endif
                                    @endif
                                @endisset
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
        <h4 class="font-medium">Data Event</h4>
        <div class="pb-3 pt-3">
            <input type="text" class="form-control mb-3 w-25" placeholder="Search..." wire:model.live="cari">
        </div>
        {{ $events->links() }}
        <table class="table table-striped table-sortable ">
            <thead>
                <tr>
                    <th class="col-md-1">No</th>
                    <th class="col-md-2 sort" >Tanggal Event</th>
                    <th class="col-md-2 sort" >Keterangan</th>
                    <th class="col-md-2 sort" >Foto</th>
                    <th class="col-md-2">Aksi</th>
                </tr>
            </thead>
            <tbody>


                @foreach ($events as $key => $value)
                <tr>

                    <td>{{ $events->firstItem() + $key }}</td>
                    <td>{{ $value->tgl_event }}</td>
                    <td>{{ $value->keterangan }}</td>
                    <td><img src="{{ asset('storage/' . $value->filename) }}" alt="Foto" class="p-0.5 object-contain rounded-full "
                                style="width: 60px; height: 60px;"></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a wire:click="show_detail({{ $value->id }})" class="btn btn-warning btn-sm">Detail</a>
                            <a wire:click="edit({{ $value->id }})" class="btn btn-warning btn-sm">Edit</a>
                            <a wire:click="konfimasihapus({{ $value->id }})" class="btn btn-danger btn-sm">Del</a>
                        </div>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>

    </div>
    <!-- AKHIR DATA -->

    <!-- Modal Konfirmasi Hapus - Full Livewire -->
    @if($showDeleteModal)
    <div class="modal fade show" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: block; background-color: rgba(0,0,0,0.5);" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Hapus Data</h1>
                    <button type="button" class="btn-close" wire:click="closeDeleteModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeDeleteModal">Tidak</button>
                    <button type="button" class="btn btn-primary" wire:click="hapus()">YA</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>







