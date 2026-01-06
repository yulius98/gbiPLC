<x-nav-bar/>
{{--<section id="hero" class="w-full bg-gradient-to-r from-purple-900 via-purple-700 to-blue-800 shadow-xl overflow-hidden">
    <div id="heroCarousel" class="relative w-full" style="background-image: url('{{ asset('BGCarousel.webp') }}'); background-size: cover; background-position: center;">
    --}}

<x-layout>
    <section id="hero" class="w-full shadow-lg shadow-gray-500 overflow-hidden" style="border-radius: 16px; background: rgba(255,255,255,0.2); backdrop-filter: blur(3px);" >
        <div id="heroCarousel" class="relative w-full">

            <!-- Carousel Indicators -->
            <div class="absolute bottom-4 left-1/2 z-10 flex -translate-x-1/2 space-x-3">
                @foreach ( $carousels as $key => $Ads)
                    <button type="button"
                        class="h-3 w-3 rounded-full bg-black/10 hover:bg-gray-600 transition-all duration-200"
                        data-carousel-indicator="{{ $key }}"
                        aria-label="Slide {{ $key + 1 }}">
                    </button>
                @endforeach
            </div>

            <div class="flex transition-transform duration-500 ease-in-out" id="carouselItems">
                @foreach ( $carousels as $key => $Ads)
                    <div class="min-w-full relative" data-carousel-item="{{ $key }}">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-8 lg:p-12 min-h-[400px] lg:min-h-[500px] items-center">
                            @php
                                $isVideo = Str::endsWith($Ads->filename, ['.mp4', '.webm', '.ogg']);
                            @endphp

                            @if ($isVideo)
                                <div class="col-span-1 lg:col-span-2 flex justify-center items-center">
                                    <video class="w-full h-64 md:h-80 lg:h-96 object-contain rounded-2xl" controls autoplay loop muted>
                                        <source src="{{ asset('storage/'. $Ads->filename) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            @else

                                <!-- Left Side - Tema dan Description -->
                                <div class="flex flex-col justify-center space-y-6 order-2 lg:order-1">
                                    @if($Ads->tema)
                                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold leading-tight" >
                                        {{ $Ads->tema }}
                                    </h2>
                                    @endif

                                    @if($Ads->description)
                                    <p class="text-base md:text-lg lg:text-xl font-semibold leading-relaxed">
                                        {!! nl2br(e($Ads->description)) !!}
                                    </p>
                                    @endif

                                    @if(!$Ads->tema && !$Ads->description)
                                    <div class="space-y-4">
                                        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold leading-tight" >
                                            Welcome to GBI PLC
                                        </h2>
                                        <p class="text-base md:text-lg lg:text-xl leading-relaxed">
                                            Gereja Bethel Indonesia Philadelphia Life Center
                                        </p>
                                    </div>
                                    @endif
                                </div>

                                <!-- Right Side - Image -->
                                <div class="flex justify-center lg:justify-end order-1 lg:order-2 mt-10">
                                    <div class="relative w-full max-w-md lg:max-w-lg">
                                        <img src="{{ asset('storage/'. $Ads->filename) }}"
                                            class="w-full h-64 md:h-80 lg:h-96 object-contain rounded-2xl"
                                            alt="Slide {{ $key + 1 }}">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Previous Control -->
            <button class="absolute left-2 lg:left-4 top-1/2 -translate-y-1/2 p-3 lg:p-4 bg-gray-500 hover:bg-black/40 rounded-full transition-all duration-200 backdrop-blur-sm z-20"
                    type="button"
                    data-carousel-prev id="prevButton">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span class="sr-only">Previous</span>
            </button>

            <!-- Next Control -->
            <button class="absolute right-2 lg:right-4 top-1/2 -translate-y-1/2 p-3 lg:p-4 bg-transparent hover:bg-white/40 rounded-full transition-all duration-200 backdrop-blur-sm z-20"
                    type="button"
                    data-carousel-next id="nextButton">
                <svg class="w-5 h-5 lg:w-6 lg:h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        const indicators = document.querySelectorAll('[data-carousel-indicator]');
        const totalItems = carouselItems.children.length;
        let currentIndex = 0;

        function updateCarousel() {
            const offset = -currentIndex * 100;
            carouselItems.style.transform = `translateX(${offset}%)`;

            // Update indicators
            indicators.forEach((indicator, index) => {
                if (index === currentIndex) {
                    indicator.classList.remove('bg-black/10');
                    indicator.classList.add('bg-gray-800');
                } else {
                    indicator.classList.remove('bg-gray-800');
                    indicator.classList.add('bg-black/10');
                }
            });
        }

        prevButton.addEventListener('click', () => {
            currentIndex = (currentIndex > 0) ? currentIndex - 1 : totalItems - 1;
            updateCarousel();
        });

        nextButton.addEventListener('click', () => {
            currentIndex = (currentIndex < totalItems - 1) ? currentIndex + 1 : 0;
            updateCarousel();
        });

        // Add click event to indicators
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentIndex = index;
                updateCarousel();
            });
        });

        // Initialize first indicator as active
        updateCarousel();
    </script>

    <!-- background layout diatur dari layout.blade.php -->
    <div style="margin: auto; padding: 40px 70px; border-radius: 8px; width: auto;  ">
        <section class="hero" id="home">
            <div class="mask-container">
                <main class="content">
                    <marquee behavior="scroll" direction="left" scrollamount="5">
                        <p style="mix-blend-mode: difference; font-family: 'Times New Roman', Times, serif; font-weight: 800; text-align: justify; text-justify: inter-word; font-size: large;">
                            Dan orang-orang bijaksana akan bercahaya seperti cahaya cakrawala dan yang telah menuntun banyak orang kepada kebenaran seperti bintang-bintang tetap untuk selama-lamanya
                            <strong class="shadow-[0_0_20px_white]">-- Daniel 12 : 3 --</strong>
                        </p>
                    </marquee>
                </main>
            </div>
        </section>
    </div>

    <!-- Popup Ads -->
    @if ($popupAds && $popupAds->count() > 0)
        @foreach ($popupAds as $popup)
            <div id="popup-{{ $popup->id }}" class="popup-overlay fixed inset-0 min-h-screen bg-transparent bg-opacity-60 z-50 flex justify-center pt-24 sm:pt-32" style="display: none;">
                <div class="popup-content bg-transparent rounded-lg shadow-xl w-full max-w-xs sm:max-w-md md:max-w-lg mx-4 flex flex-col items-center relative" style="margin:0 auto;">
                    <button class="popup-close absolute top-2 right-0  text-red-500 text-4xl font-extrabold z-10"
                            onclick="closePopup('popup-{{ $popup->id }}')">
                        &times;
                    </button>
                    <div class="p-4 w-full flex flex-col items-center">
                        <img src="{{ asset('storage/'. $popup->filename) }}"
                             alt="Popup Ad {{ $popup->id }}"
                             class="w-full max-w-xs sm:max-w-md md:max-w-lg h-auto rounded-lg mx-auto object-contain" style="max-height:60vh;" loading="lazy">
                    </div>
                </div>
            </div>
        @endforeach

        <script>
            let currentPopupIndex = 0;
            const popups = @json($popupAds->pluck('id'));
            const popupDelay = 3000; // 3 seconds between popups
            const initialDelay = 2000; // 2 seconds after page load

            function showPopup(popupId) {
                const popup = document.getElementById(`popup-${popupId}`);
                if (popup) {
                    popup.style.display = 'block';
                    popup.classList.remove('hidden');
                    // Allow scrolling when popup is open
                }
            }

            function closePopup(popupId) {
                const popup = document.getElementById(popupId);
                if (popup) {
                    popup.style.display = 'none';
                    popup.classList.add('hidden');
                    // Scrolling remains available

                    // Show next popup after delay
                    setTimeout(() => {
                        showNextPopup();
                    }, popupDelay);
                }
            }

            function showNextPopup() {
                if (currentPopupIndex < popups.length) {
                    showPopup(popups[currentPopupIndex]);
                    currentPopupIndex++;
                }
            }

            // Auto-close popup after 10 seconds
            function autoClosePopup(popupId) {
                setTimeout(() => {
                    closePopup(`popup-${popupId}`);
                }, 10000);
            }

            // Start showing popups after page load
            window.addEventListener('load', () => {
                if (popups.length > 0) {
                    setTimeout(() => {
                        showNextPopup();
                        if (popups[currentPopupIndex - 1]) {
                            autoClosePopup(popups[currentPopupIndex - 1]);
                        }
                    }, initialDelay);
                }
            });

            // Close popup when clicking outside
            document.addEventListener('click', (e) => {
                if (e.target.classList.contains('popup-overlay')) {
                    const popupId = e.target.id;
                    closePopup(popupId);
                }
            });

            // Close popup with Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    const activePopup = document.querySelector('.popup-overlay:not(.hidden)');
                    if (activePopup) {
                        closePopup(activePopup.id);
                    }
                }
            });
        </script>
    @endif
    <!-- End Popup Ads -->

    <!-- Suara Gembala -->
    <div class="mt-4 pt-10 pb-8 w-full mx-auto shadow-lg shadow-gray-500" style="border-radius: 16px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px);">
        @if ($latestPastorNote)
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-8 tracking-wide" >Suara Gembala</h2>
            <div class="grid grid-cols-1 md:grid-cols-1 gap-8 items-center">
                <div class="flex justify-center">
                    <img src="{{ asset('storage/'. $latestPastorNote->filename) }}" alt="foto" class="max-h-72 rounded-3xl shadow-lg border-4 border-purple-200" />
                </div>
                <div class="flex flex-col justify-center space-y-3 p-8">
                    <time class="text-sm">Tanggal : {{ \Carbon\Carbon::parse($latestPastorNote->tgl_note)->format('d-m-Y') }}</time>
                    <p class="font-semibold text-lg leading-relaxed">{!! nl2br(e($latestPastorNote->note)) !!}</p>
                </div>
            </div>
        @endif
    </div>
    <!-- END Suara Gembala -->

    <!-- Event -->
    <section id="event" class="py-24 shadow-lg shadow-gray-500" style="margin-top:40px; border-radius: 16px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(3px);">
        <div class="mx-auto grid max-w-7xl gap-16 px-6 lg:px-8 xl:grid-cols-3">
            <div class="max-w-xl">
                <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-6">Event GBI Philadelphia Life Center</h2>
            </div>
            <ul role="list" class="grid gap-x-8 gap-y-12 sm:grid-cols-2 sm:gap-y-16 xl:col-span-2">
                @forelse ( $events as $event )
                <li>
                    <div class="items-center grid grid-cols-1 lg:grid-cols-1 gap-x-6">
                        <img class=" size-72 rounded-3xl " src="{{ asset('storage/'. $event->filename)}}" alt="Event {{ $event->keterangan }}" />
                        <div>
                            <h3 class="text-base/7 font-semibold tracking-tight ">{{ ucwords($event->keterangan) }}</h3>
                            <p class="text-sm">{{ $event->formatted_date }}</p>
                        </div>
                    </div>
                </li>
                @empty
                <li class="col-span-2">
                    <div class="text-center py-12">
                        <h3 class="text-lg font-semibold mb-2">Tidak ada event bulan ini</h3>
                    </div>
                </li>
                @endforelse
            <!-- More people... -->
            </ul>
            <div class="mt-6">
                {{ $events->links() }}
            </div>
        </div>
    </section>


    <!-- Jemaat Ultah -->
    @auth
        @if ($birthdayMembers->count() > 0)
        <section id="birthday" class="py-24 shadow-lg shadow-grey" style="margin-top:40px; border-radius: 16px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(3px);">

            <div class=" bg-transparent py-20 sm:py-28 w-full mt-10">
                <div class="mx-auto grid max-w-7xl gap-16 px-6 lg:px-8 xl:grid-cols-3">
                    <div class="max-w-xl">
                        <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-6">Jemaat GBI Philadelphia Berulang Tahun Bulan {{ \Carbon\Carbon::now()->translatedFormat('F') }}</h2>
                    </div>
                    <ul role="list" class="grid gap-x-8 gap-y-12 sm:grid-cols-2 sm:gap-y-16 xl:col-span-2">
                        @foreach ( $birthdayMembers as $jemaat)
                        <li>
                            <div class="flex items-center gap-x-6 bg-black/60 rounded-xl p-4 shadow-lg">
                                <img class="w-16 h-16 rounded-full border-2 border-purple-100 object-cover" src="{{ asset('storage/'. $jemaat->filename)}}" alt="" />
                                <div>
                                    <h3 class="text-lg font-semibold tracking-tight ">{{ ucwords($jemaat->name) }}</h3>
                                    <h4 class="text-sm font-semibold">{{ \Carbon\Carbon::parse($jemaat->tgl_lahir)->translatedFormat('d F') }}</h4>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <div class="mt-6">
                        {{ $birthdayMembers->links() }}
                    </div>
                </div>
            </div>

        </section>
        @endif
    @endauth


</x-layout>
{{--<audio id="bgMusic" src="{{ asset('musics/christmas.mp3') }}" autoplay loop hidden></audio> --}}
{{-- Footer Section --}}
<footer
    class="text-white py-10 mt-auto w-full"
    role="contentinfo"
    aria-label="Footer"
    >
    <div class="px-6 md:px-12 lg:px-16 relative">
        {{-- Large Year Background
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-0">
            <div class="text-[150px] lg:text-[200px] xl:text-[250px] font-bold leading-none opacity-20 select-none"
                 style="background: linear-gradient(135deg, rgba(168,85,247,0.4) 0%, rgba(59,130,246,0.4) 50%, rgba(34,197,94,0.4) 100%);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        background-clip: text;
                        text-shadow: 0 0 40px rgba(168,85,247,0.3);">
                {{ date('Y') }}
            </div>
        </div> --}}

        {{-- Main Content Container --}}
        <div class="flex flex-col md:flex-row justify-between items-start gap-8 relative z-10">
            {{-- Left Section: Church Leadership --}}
            <div class="text-left space-y-6 max-w-md">
                {{-- Pastors Section --}}
                <div>
                    <h3 class="font-semibold text-lg mb-3 text-white">
                        Pemimpin Gereja
                    </h3>
                    <div class="space-y-2">
                        <div>
                            <h4 class="font-medium text-white">
                                Gembala
                            </h4>
                            <p class="text-white text-sm">Pdm. DR. Jimmy Sugiarto, S.PSI, M.TH</p>
                        </div>
                        <div>
                            <h4 class="font-medium text-white">
                                Wakil Gembala
                            </h4>
                            <p class="text-white text-sm">Fredy Budiman SE, MTh</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Section: Church Info --}}
            <div class="text-left md:text-right space-y-6 max-w-md">
                <div>
                    <h3 class="font-semibold text-lg mb-3 text-white">
                        GBI Philadelphia Life Center
                    </h3>
                    <div class="space-y-2">
                        <div>
                            <h4 class="font-medium text-white text-sm">Alamat</h4>
                            <p class="text-white text-sm leading-relaxed">
                                Jl. Babarsari No.45, Janti, Caturtunggal
                            </p>
                            <p class="text-white text-sm leading-relaxed">
                                Kec. Depok, Kabupaten Sleman
                            </p>
                            <p class="text-white text-sm leading-relaxed">
                                Daerah Istimewa Yogyakarta 55281
                            </p>
                        </div>
                        <div>
                            <h4 class="font-medium text-white text-sm">Kontak</h4>
                            <a
                                href="https://wa.me/6285336618852"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center text-white hover:text-purple-300 font-bold hover:underline transition-colors duration-200 text-sm md:justify-end"
                                aria-label="Hubungi kami via WhatsApp di 0853-3661-8852"
                            >
                                <span class="mr-2">📞</span>
                                Telp: 0853-3661-8852
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Copyright Section --}}
        <div class="mt-8 pt-6 ">
            <div class="text-center">
                <p class="text-sm text-white">
                    &copy; {{ date('Y') }} GBI Philadelphia Life Center. All rights reserved.
                </p>
                <p class="text-xs text-white mt-1">
                    Built with love for the Kingdom of God
                </p>
            </div>
        </div>
    </div>
</footer>
{{-- End Footer Section --}}

<style>
    footer::before {
    content: "";
    position: absolute;
    inset: 0;
    background-color: black;
    background-image: url('{{ asset('BGFooter.webp') }}');

    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    z-index: 0;
    }
    footer {
        position: relative;
        /* ...existing styles... */
    }
    footer > * {
        position: relative;
        z-index: 1;
    }
</style>
