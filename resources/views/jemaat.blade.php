<x-nav-bar/>
<x-layout>
    <div class="bg-black py-24 sm:py-32">
        <div class="mx-auto grid max-w-7xl gap-20 px-6 lg:px-8 xl:grid-cols-3">
            <div class="max-w-xl">
                <h2 class="text-3xl font-semibold tracking-tight text-pretty text-white sm:text-4xl">Jemaat GBI Philadelphia Life Center</h2>
                <p class="mt-6 text-lg/8 text-white">Dan orang-orang bijaksana akan bercahaya seperti cahaya cakrawala dan yang telah menuntun banyak orang kepada kebenaran seperti bintang-bintang tetap untuk selama-lamanya </p>
            </div>
            
                <ul role="list" class="grid gap-x-8 gap-y-12 sm:grid-cols-2 sm:gap-y-16 xl:col-span-2">
                    @foreach ( $dtjemaat as $dtjemaatPLC )
                    <li>
                        <div class="flex items-center gap-x-6">
                            <img class="size-16 rounded-full" src="{{ asset('storage/'. $dtjemaatPLC->foto)}}" alt="" />
                            <div>
                                <h3 class="text-base/7 font-semibold tracking-tight text-white">{{ ucwords($dtjemaatPLC->name) }}</h3>
                                <h4 class="text-sm/6 font-semibold text-white">
                                        {{ \Carbon\Carbon::parse($dtjemaatPLC->tgl_lahir)->format('d-m-Y') }}
                                </h4>
                                <p class="text-sm/6 font-semibold text-white">
                                        {{ ucwords($dtjemaatPLC->alamat) }}
                                </p>
                            </div>
                        </div>
                    </li>
                    @endforeach
                <!-- More people... -->
                </ul>    

                <div class="mt-6 text-white ">
                    {{ $dtjemaat->links() }}
                </div>
            
            
            
        </div>
    </div>










    {{-- <div class="bg-black">
        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <h2 class="text-2xl font-bold tracking-tight text-white">Data Jemaat GBI PLC</h2>
            <div class="mt-6 grid grid-rows-4 grid-flow-col gap-x-6 gap-y-10 xl:gap-x-8">
                @foreach ( $dtjemaat as $dtjemaatPLC )
                    <div class="group relative">
                        <img src="{{ asset('storage/'. $dtjemaatPLC->foto)}}" alt="Front of men&#039;s Basic Tee in black." class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75" />
                        <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-sm text-white">Nama : {{ $dtjemaatPLC->name }} </h3> 
                            <h4 class="text-sm text-white">Tanggal Lahir : {{ $dtjemaatPLC->tgl_lahir }} </h4>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- More products... -->
            </div>
        </div>
        </div>--}}
</x-layout>

