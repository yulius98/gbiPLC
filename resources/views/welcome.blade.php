<x-nav-bar/>
<x-layout>
    <div style="height: 100vh; width: 100%; background-size: cover; background-position: center; position: absolute; top: 0; left: 0; z-index: -1;"></div>
    <div style="margin: auto; padding: 50px; border-radius: 8px; width: auto;">
        <section class="hero" id="home">
            <div class="mask-container">
                <main class="content">
                    <h1 style="font-size: xx-large; color: #D8B4FE; text-align: center; font-family: 'Times New Roman', Times, serif; font-weight: 400; text-shadow: 0 0 5px #D8B4FE, 0 0 10px #D8B4FE, 0 0 20px #A855F7;">
                        WELCOME HOME
                    </h1>
                    <marquee behavior="scroll" direction="left" scrollamount="5">
                        <p style="color: white; mix-blend-mode: difference; font-family: 'Times New Roman', Times, serif; font-weight: 400; text-align: justify; text-justify: inter-word; font-size: medium;">
                            Dan orang-orang bijaksana akan bercahaya seperti cahaya cakrawala dan yang telah menuntun banyak orang kepada kebenaran seperti bintang-bintang tetap untuk selama-lamanya 
                            <strong class="text-white shadow-[0_0_20px_white]">-- Daniel 12 : 3 --</strong>
                        </p>
                    </marquee>
                </main>
            </div>
        </section>
    </div>
    <section id="hero" class="mt-7 bg-black">
        <div id="heroCarousel" class="relative w-full rounded-3xl overflow-hidden">
            <!-- Carousel Indicators -->
            <div class="absolute bottom-4 left-1/2 z-10 flex -translate-x-1/2 space-x-2">
                @foreach ( $dtcarousel as $key => $Ads)
                    <button type="button" 
                        class="h-2 w-2 rounded-full bg-white/50 hover:bg-white/80 transition-colors duration-200"
                        data-carousel-indicator="{{ $key }}"
                        aria-label="Slide {{ $key + 1 }}">
                    </button>
                @endforeach
            </div>
    
            <div class="flex  transition-transform duration-500 ease-in-out" id="carouselItems">
                @foreach ( $dtcarousel as $key => $Ads)
                    <div class="min-w-full relative" data-carousel-item="{{ $key }}">
                        <img src="{{ asset('storage/'. $Ads->filename) }}" 
                            class="w-full h-96 md:h-[500px] object-cover" 
                            alt="Slide {{ $key + 1 }}">
                        <!--<div class="absolute inset-0 bg-black bg-opacity-30"></div> -->
                    </div>
                @endforeach
            </div>
    
            <!-- Previous Control -->
            <button class="absolute left-0 top-1/2 -translate-y-1/2 p-4 bg-black bg-opacity-30 hover:bg-opacity-50 transition-colors duration-200" 
                    type="button" 
                    data-carousel-prev id="prevButton">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span class="sr-only">Previous</span>
            </button>
    
            <!-- Next Control -->
            <button class="absolute right-0 top-1/2 -translate-y-1/2 p-4 bg-black bg-opacity-30 hover:bg-opacity-50 transition-colors duration-200" 
                    type="button" 
                    data-carousel-next id="nextButton">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="sr-only">Next</span>
            </button>
        </div>
    </section>
    
    <script>
        const carouselItems = document.getElementById('carouselItems');
        const prevButton = document.getElementById('prevButton');
        const nextButton = document.getElementById('nextButton');
        const totalItems = carouselItems.children.length;
        let currentIndex = 0;

        function updateCarousel() {
            const offset = -currentIndex * 100;
            carouselItems.style.transform = `translateX(${offset}%)`;
        }

        prevButton.addEventListener('click', () => {
            currentIndex = (currentIndex > 0) ? currentIndex - 1 : totalItems - 1;
            updateCarousel();
        });

        nextButton.addEventListener('click', () => {
            currentIndex = (currentIndex < totalItems - 1) ? currentIndex + 1 : 0;
            updateCarousel();
        });
    </script>

    <!-- Suara Gembala -->
    <div style="margin-top: 4rem;" class="border-t-5 border-gray-200">
        <div>
            @if ($dtpasstornote)
                <div>
                    <h2 class=" mt-4 w-full text-center text-4xl font-semibold text-pretty text-white sm:text-5xl">Suara Gembala</h2>
                </div>
                <div class=" border-t border-gray-200 pt-50 sm:mt-10 sm:pt-16 lg:mx-0">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">
                        <div class="flex justify-center px-1 lg:justify-start lg:px-0 sm:mt-10">
                            <img src="{{ asset('storage/'. $dtpasstornote->filename) }}" alt="foto" class=" w-auto rounded-3xl bg-gray-50 sm:rounded-3xl" />
                        </div>
                        <div class="flex flex-col justify-center space-y-2">
                            <time class="text-gray-200 text-sm" datetime="{{ \Carbon\Carbon::parse($dtpasstornote->tgl_note)->format('Y-m-d') }}">
                                Tanggal : {{ \Carbon\Carbon::parse($dtpasstornote->tgl_note)->format('d-m-Y') }}
                            </time>
                            <p class="text-white text-lg leading-relaxed">{{ $dtpasstornote->note }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- END Suara Gembala -->

    <!-- Jemaat Ultah -->
            @if ($dtjemaatultah->count() > 0)
        <div class="bg-black py-24 sm:py-32">
        <div class="mx-auto grid max-w-7xl gap-20 px-6 lg:px-8 xl:grid-cols-3">
            <div class="max-w-xl">
                <h2 class="text-3xl font-semibold tracking-tight text-pretty text-white sm:text-4xl">
                    Jemaat GBI Philadelphia Berulang Tahun Bulan {{ \Carbon\Carbon::now()->translatedFormat('F') }}
                </h2>   
            </div>
            
            <ul role="list" class="grid gap-x-8 gap-y-12 sm:grid-cols-2 sm:gap-y-16 xl:col-span-2">
                @foreach ( $dtjemaatultah as $jemaat)
                <li>
                    <div class="flex items-center gap-x-6">
                        <img class="size-16 rounded-full" src="{{ asset('storage/'. $jemaat->filename)}}" alt="" />
                        <div>
                            <h3 class="text-base/7 font-semibold tracking-tight text-white">{{ ucwords($jemaat->name) }}</h3>
                            <h4 class="text-sm/6 font-semibold text-white">
                                {{ \Carbon\Carbon::parse($jemaat->tgl_lahir)->translatedFormat('d F') }}
                            </h4>

                        </div>
                    </div>
                </li>
                @endforeach
            <!-- More people... -->
            </ul>    

            <div class="mt-6 text-white ">
                {{ $dtjemaatultah->links() }}
            </div>  
        </div>
    </div>    
        
    @endif

    <footer class="bg-gray-200 text-black py-8 mt-12">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="font-semibold text-lg mb-2">Gembala</h3>
                <p>Pdm. DR. Jimmy Sugiarto, S.PSI, M.TH</p>
                <h4 class="font-semibold text-lg mb-2">Wakil Gembala</h4>
                <p>Pdm. Korinta Jati</p>
            </div>
            <div>
                <h3 class="font-semibold text-lg mb-2">GBI Philadelphia Life Center</h3>
                <p>Alamat : Jl. Babarsari No.45, Janti, Caturtunggal, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281</p>
                <a style="wa-link:hover; font-weight:bold" href="https://wa.me/6285336618852" target="_blank">Telp : 0853-3661-8852</a>

            </div>
            <!--<div>
                <h3 class="font-semibold text-lg mb-2">Pelayanan Konsening</h3>
                <p>No Telp :</p>
            </div>
            <div>
                <h3 class="font-semibold text-lg mb-2">Pelayanan Doa</h3>
                <p>No. Telp :</p>
            </div> -->
        </div>
    </footer>
            


</x-layout>
