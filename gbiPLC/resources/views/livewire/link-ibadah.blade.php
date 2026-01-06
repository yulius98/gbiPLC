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
                        <label for="tgl_ibadah" class="col-sm-3 col-form-label">Tanggal Ibadah</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" id="tgl_ibadah" wire:model="tgl_ibadah">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="ibadah_ke" class="col-sm-3 col-form-label">Ibadah</label>
                        <div class="col-sm-9">
                            <select id="ibadah_ke" name="ibadah_ke"
                                class="form-control"
                                wire:model="ibadah_ke">
                                <option value="">-- Pilih Ibadah --</option>
                                <option value="Ibadah 1">Ibadah I</option>
                                <option value="Ibadah 2">Ibadah II</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="link_ibadah" class="col-sm-3 col-form-label">Link Ibadah</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="link_ibadah" wire:model="link_ibadah">
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
        <h4 class="font-medium">Data Ibadah Raya</h4>
        <div class="pb-3 pt-3">
            <input type="text" class="form-control mb-3 w-25" placeholder="Search..." wire:model.live="cari">
        </div>
        {{ $ibadah->links() }}
        <table class="table table-striped table-sortable ">
            <thead>
                <tr>
                    <th class="col-md-1">No</th>
                    <th class="col-md-4 sort" @if ($sortcolom == 'tgl_ibadah') {{ $sortdirection }} @endif wire:click="sort('tgl_ibadah')">Tanggal Ibadah</th>
                    <th class="col-md-3 sort" >Ibadah Ke</th>
                    <th class="col-md-2 sort" >Link Ibadah</th>
                    <th class="col-md-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ibadah as $key => $value)

                <tr>
                    <td>{{ $ibadah->firstItem() + $key }}</td>
                    <td>{{ $value->tgl_ibadah  }}</td>
                    <td>{{ $value->ibadah_ke }}</td>
                    <td>{{ $value->link_ibadah }}</td>
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







