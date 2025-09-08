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
    <section id="hero" class="w-full max-w-5xl mx-auto mt-7 bg-black/80 rounded-3xl shadow-xl">
        <div id="heroCarousel" class="relative w-full rounded-3xl overflow-hidden">
            <!-- Carousel Indicators -->
            <div class="absolute bottom-4 left-1/2 z-10 flex -translate-x-1/2 space-x-2">
                @foreach ( $carousels as $key => $Ads)
                    <button type="button"
                        class="h-2 w-2 rounded-full bg-white/50 hover:bg-white/80 transition-colors duration-200"
                        data-carousel-indicator="{{ $key }}"
                        aria-label="Slide {{ $key + 1 }}">
                    </button>
                @endforeach
            </div>

            <div class="flex transition-transform duration-500 ease-in-out" id="carouselItems">
                @foreach ( $carousels as $key => $Ads)
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
    <div class="mt-16 border-t border-gray-700 pt-10 pb-8 w-full max-w-5xl mx-auto">
        @if ($latestPastorNote)
            <h2 class="text-3xl md:text-4xl font-bold text-center text-purple-200 mb-8 tracking-wide">Suara Gembala</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="flex justify-center">
                    <img src="{{ asset('storage/'. $latestPastorNote->filename) }}" alt="foto" class="max-h-72 rounded-3xl shadow-lg border-4 border-purple-200 bg-gray-50" />
                </div>
                <div class="flex flex-col justify-center space-y-3">
                    <time class="text-gray-300 text-sm">Tanggal : {{ \Carbon\Carbon::parse($latestPastorNote->tgl_note)->format('d-m-Y') }}</time>
                    <p class="text-white text-lg leading-relaxed">{{ $latestPastorNote->note }}</p>
                </div>
            </div>
        @endif
    </div>
    <!-- END Suara Gembala -->

    <!-- Jemaat Ultah -->
    @if ($birthdayMembers->count() > 0)
    <div class=" bg-transparent py-20 sm:py-28 w-full mt-10">
        <div class="mx-auto grid max-w-7xl gap-16 px-6 lg:px-8 xl:grid-cols-3">
            <div class="max-w-xl">
                <h2 class="text-3xl md:text-4xl font-bold tracking-tight text-purple-200 mb-6">Jemaat GBI Philadelphia Berulang Tahun Bulan {{ \Carbon\Carbon::now()->translatedFormat('F') }}</h2>
            </div>
            <ul role="list" class="grid gap-x-8 gap-y-12 sm:grid-cols-2 sm:gap-y-16 xl:col-span-2">
                @foreach ( $birthdayMembers as $jemaat)
                <li>
                    <div class="flex items-center gap-x-6 bg-black/60 rounded-xl p-4 shadow-lg">
                        <img class="w-16 h-16 rounded-full border-2 border-purple-300 object-cover" src="{{ asset('storage/'. $jemaat->filename)}}" alt="" />
                        <div>
                            <h3 class="text-lg font-semibold tracking-tight text-white">{{ ucwords($jemaat->name) }}</h3>
                            <h4 class="text-sm font-semibold text-purple-200">{{ \Carbon\Carbon::parse($jemaat->tgl_lahir)->translatedFormat('d F') }}</h4>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            <div class="mt-6 text-white">
                {{ $birthdayMembers->links() }}
            </div>
        </div>
    </div>
    @endif

    <footer class="bg-gray-900 text-purple-100 py-10 mt-16 border-t border-purple-900 ">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="font-semibold text-lg mb-0">Gembala</h3>
                <h4 class="mt-0 mb-2">Pdm. DR. Jimmy Sugiarto, S.PSI, M.TH</h4>
                <h5 class="font-semibold text-lg mb-0">Wakil Gembala</h5>
                <h6 class="mt-0">Fredy Budiman SE, MTh</h6>
            </div>
            <div>
                <h3 class="font-semibold text-lg mb-2">GBI Philadelphia Life Center</h3>
                <p class="mb-2">Alamat : Jl. Babarsari No.45, Janti, Caturtunggal, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281</p>
                <a class="hover:underline font-bold text-purple-300" href="https://wa.me/6285336618852" target="_blank">Telp : 0853-3661-8852</a>
            </div>
        </div>
        <div class="text-center text-xs text-purple-400 mt-8">&copy; {{ date('Y') }} GBI Philadelphia Life Center. All rights reserved.</div>
    </footer>



</x-layout>

