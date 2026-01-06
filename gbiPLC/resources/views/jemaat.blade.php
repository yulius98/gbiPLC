<x-nav-bar/>
<x-layout>
    <div class="bg-black py-24 sm:py-32">
        <div class="mx-auto grid max-w-7xl gap-20 px-6 lg:px-8 xl:grid-cols-3">
            <div class="max-w-xl">
                <h2 class="text-3xl font-semibold tracking-tight text-pretty text-white sm:text-4xl">Event GBI Philadelphia Life Center</h2>

            </div>

                <ul role="list" class="grid gap-x-8 gap-y-12 sm:grid-cols-2 sm:gap-y-16 xl:col-span-2">
                    @forelse ( $events as $event )
                    <li>
                        <div class="items-center grid grid-cols-1 lg:grid-cols-2 gap-x-6">
                            <img class=" size-72 rounded-3xl " src="{{ asset('storage/'. $event->filename)}}" alt="Event {{ $event->keterangan }}" />
                            <div>
                                <h3 class="text-base/7 font-semibold tracking-tight text-white">{{ ucwords($event->keterangan) }}</h3>
                                <p class="text-sm text-gray-300">{{ $event->formatted_date }}</p>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="col-span-2">
                        <div class="text-center py-12">
                            <h3 class="text-lg font-semibold text-white mb-2">Tidak ada event bulan ini</h3>
                            <p class="text-gray-400">Silakan cek kembali bulan depan untuk event terbaru.</p>
                        </div>
                    </li>
                    @endforelse
                <!-- More people... -->
                </ul>

                @if($events->hasPages())
                <div class="mt-6 text-white ">
                    {{ $events->links() }}
                </div>
                @endif

        </div>
    </div>

</x-layout>

