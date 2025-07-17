<x-nav-bar/>
<x-layout>
    <div class="bg-black py-24 sm:py-32">
        <div class="mx-auto grid max-w-7xl gap-20 px-6 lg:px-8 xl:grid-cols-3">
            <div class="max-w-xl">
                <h2 class="text-3xl font-semibold tracking-tight text-pretty text-white sm:text-4xl">Event GBI Philadelphia Life Center</h2>
                <p class="mt-6 text-lg/8 text-white">Dan orang-orang bijaksana akan bercahaya seperti cahaya cakrawala dan yang telah menuntun banyak orang kepada kebenaran seperti bintang-bintang tetap untuk selama-lamanya </p>
            </div>
            
                <ul role="list" class="grid gap-x-8 gap-y-12 sm:grid-cols-2 sm:gap-y-16 xl:col-span-2">
                    @foreach ( $dtevent as $event )
                    <li>
                        <div class="items-center grid grid-cols-1 lg:grid-cols-2 gap-x-6">
                            <img class=" size-72 rounded-3xl " src="{{ asset('storage/'. $event->filename)}}" alt="" />
                            <div>
                                <h3 class="text-base/7 font-semibold tracking-tight text-white">{{ ucwords($event->keterangan) }}</h3>
                            </div>
                        </div>
                    </li>
                    @endforeach
                <!-- More people... -->
                </ul>    

                <div class="mt-6 text-white ">
                    {{ $dtevent->links() }}
                </div>
    
        </div>
    </div>
   
</x-layout>

