<x-nav-bar/>
<x-layout>
    <div class=" bg-transparent py-24 sm:py-32">
        <div class="mx-auto grid max-w-7xl gap-20 px-6 lg:px-8 xl:grid-cols-3">
            <div class="max-w-xl">
                <h2 class="text-3xl font-semibold tracking-tight text-pretty sm:text-4xl">Life Group GBI Philadelphia Life Center</h2>

            </div>

                <ul role="list" class="grid gap-x-8 gap-y-12 sm:grid-cols-2 sm:gap-y-16 xl:col-span-2">
                    @forelse ( $komsel as $item )
                    <li>
                        <div class="items-center grid grid-cols-1 lg:grid-cols-1 gap-x-6 bg-gray-900 p-6 rounded-lg shadow-lg border border-white">
                            <div>
                                <h3 class="text-base/7 font-semibold tracking-tight text-white">Nama Life Group : {{ $item->nama_komsel }}</h3>
                                <h4 class="text-sm text-white">Ketua : {{ $item->ketua_komsel }}</h4>
                                <h5 class="text-sm text-white">No Telp : {{ $item->no_telp }}</h5>
                                <p class="text-sm text-white">Alamat : {{ $item->alamat }}</p>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="col-span-2">
                        <div class="text-center py-12">
                            <h3 class="text-lg font-semibold text-white mb-2">Belum Ada Daftar Komsel</h3>
                        </div>
                    </li>
                    @endforelse
                <!-- More people... -->
                </ul>

                @if($komsel->hasPages())
                <div class="mt-6 text-white ">
                    {{ $komsel->links() }}
                </div>
                @endif

        </div>
    </div>

</x-layout>

