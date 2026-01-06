@php use Illuminate\Support\Facades\Storage; @endphp
@php
    use Carbon\Carbon;
    $namaBulan = Carbon::now()->translatedFormat('F');
@endphp


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

    <div class="row">
           
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">Jemaat Baru</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <div class="small text-white">Jemaat Baru : {{ $jmljemaat_baru }}</div>            
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">Jemaat yang belum pernah dikunjungi</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <div class="small text-white">Total : {{ $dtjemaat_belumpernah_kunjungan }}</div>            
                </div>
            </div>
        </div>
        
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">Kunjungan Bulan {{ $namaBulan }}</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <div class="small text-white">Total kunjungan bulan ini : {{ $dtkunjungan_bln_ini }}</div>            
                </div>
            </div>
        </div>
        
        
    </div>

    

    <!-- START DATA -->
    
    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <h4 class="font-medium">Jemaat Baru</h4>
        <div class="pb-3 pt-3">
            <input type="text" class="form-control mb-3 w-25" placeholder="Search..." wire:model.live="cari">
        </div>
        {{ $dtjemaat_baru->links() }}
        <table class="table table-striped table-sortable ">
            <thead>
                <tr>
                    <th class="col-md-1">No</th>
                    <th class="col-md-4 sort" >Nama</th>
                    <th class="col-md-2 sort" >Alamat</th>
                    <th class="col-md-2 sort" >No HP</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($dtjemaat_baru as $key => $value)
                <tr>
                    <td>{{ $dtjemaat_baru->firstItem() + $key }}</td>
                    <td>{{ $value->name  }}</td>
                    <td>{{ $value->alamat }}</td>
                    <td>{{ $value->no_HP }}</td> 
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="my-3 p-3 bg-body rounded shadow-sm">
        <h4 class="font-medium">Jemaat yang belum pernah dikunjungi</h4>
        <div class="pb-3 pt-3">
            <input type="text" class="form-control mb-3 w-25" placeholder="Search..." wire:model.live="cari">
        </div>
        {{ $jemaat_belumpernah_kunjungan->links() }}
        <table class="table table-striped table-sortable ">
            <thead>
                <tr>
                    <th class="col-md-1">No</th>
                    <th class="col-md-4 sort" >Nama</th>
                    <th class="col-md-2 sort" >Alamat</th>
                    <th class="col-md-2 sort" >No HP</th>
                </tr>
            </thead>
            <tbody>
                
                
            @foreach ($jemaat_belumpernah_kunjungan as $key => $value)
                <tr>
                    <td>{{ $jemaat_belumpernah_kunjungan->firstItem() + $key }}</td>
                    <td>{{ $value->name  }}</td>
                    <td>{{ $value->alamat }}</td>
                    <td>{{ $value->no_HP }}</td>
                    
                </tr>
            @endforeach
                
            </tbody>
        </table>
        
    </div>
    
</div>





    

