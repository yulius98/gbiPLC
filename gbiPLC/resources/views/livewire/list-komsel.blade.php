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
                        <label for="nama_komsel" class="col-sm-3 col-form-label">Nama Life Group</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nama_komsel" wire:model="nama_komsel">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="ketua_komsel" class="col-sm-3 col-form-label">Ketua Life Group</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="ketua_komsel" wire:model="ketua_komsel">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="no_telp" class="col-sm-3 col-form-label">Nomor Telp</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="no_telp" wire:model="no_telp">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="alamat" wire:model="alamat">
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
        <h4 class="font-medium">Daftar Life Group</h4>
        <div class="pb-3 pt-3">
            <input type="text" class="form-control mb-3 w-25" placeholder="Search..." wire:model.live="cari">
        </div>
        {{ $komsel->links() }}
        <table class="table table-striped table-sortable ">
            <thead>
                <tr>
                    <th class="col-md-1">No</th>
                    <th class="col-md-4 sort" @if ($sortcolom == 'nama_komsel') {{ $sortdirection }} @endif wire:click="sort('nama_komsel')">Nama Life Group</th>
                    <th class="col-md-3 sort" >Ketua Life Group</th>
                    <th class="col-md-2 sort" >No Telp</th>
                    <th class="col-md-2 sort" >Alamat</th>
                    <th class="col-md-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($komsel as $key => $value)

                <tr>
                    <td>{{ $komsel->firstItem() + $key }}</td>
                    <td>{{ $value->nama_komsel  }}</td>
                    <td>{{ $value->ketua_komsel  }}</td>
                    <td>{{ $value->no_telp }}</td>
                    <td>{{ $value->alamat }}</td>
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








