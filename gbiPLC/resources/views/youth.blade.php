<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youth GBI Philadelphia Life Center</title>
    <link rel="icon" type="image/png" href="https://customer-assets.emergentagent.com/job_youthgbi-philly/artifacts/bxjvqwqd_logoplc.png">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <!-- <link rel="stylesheet" href="assets/css/style.css"> -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body class="bg-black text-white overflow-x-hidden">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 transition-all duration-300" id="navbar">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('logoplc.png') }}" alt="Logo" class="h-12 w-12 object-contain">
                    <div>
                        <h1 class="text-xl font-bold text-gold">YOUTH</h1>
                        <p class="text-xs text-gray-400">GBI Philadelphia Life Center</p>
                    </div>
                </div>
                
                <!-- Desktop Menu -->
                <ul class="hidden md:flex space-x-8 items-center">
                    <li><a href="#home" class="nav-link">Home</a></li>
                    <li><a href="#about" class="nav-link">Tentang</a></li>
                    <li><a href="#programs" class="nav-link">Program</a></li>
                    <li><a href="#gallery" class="nav-link">Galeri</a></li>
                    <li><a href="#schedule" class="nav-link">Jadwal</a></li>
                    <li><a href="/" class="cta-button">&#11013; Back to Main Home</a></li>
                </ul>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden text-white" id="mobile-menu-button">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div class="hidden md:hidden mt-4" id="mobile-menu">
                <ul class="flex flex-col space-y-4">
                    <li><a href="#home" class="nav-link block">Home</a></li>
                    <li><a href="#about" class="nav-link block">Tentang</a></li>
                    <li><a href="#programs" class="nav-link block">Program</a></li>
                    <li><a href="#gallery" class="nav-link block">Galeri</a></li>
                    <li><a href="#schedule" class="nav-link block">Jadwal</a></li>
                    <li><a href="/" class="cta-button">&#11013; Back to Main Home</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Video Background -->
    <section id="home" class="relative h-screen flex items-center justify-center overflow-hidden">
        <!-- Video Background -->
        <video autoplay loop playsinline class="absolute top-0 left-0 w-full h-full object-cover">
            <source src="{{ asset('videos/youth.mp4') }}" type="video/mp4">
        </video>


        <!-- Dark Overlay -->
        <div class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-60"></div>
        
        <!-- Content -->
        <div class="relative z-10 text-center px-6" data-aos="fade-up">
            <h1 class="hero-title mb-6">
                TURN MANY TO
                <span class="text-gold">RIGHTEOUSNESS</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-gray-300 max-w-3xl mx-auto">
                Generasi muda yang penuh gairah, energi positif, dan semangat dalam melayani Tuhan
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="#about" class="cta-button-large">Kenali Kami</a>
                <a href="#programs" class="cta-button-outline">Lihat Program</a>
            </div>
            
            <!-- Scroll Indicator -->
            <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
                <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-24 bg-gradient-to-b from-black via-gray-900 to-black relative overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-gold opacity-5 rounded-full blur-3xl"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-4xl mx-auto text-center" data-aos="fade-up">
                <h2 class="section-title mb-6">
                    Tentang <span class="text-gold">Youth Ministry</span>
                </h2>
                <div class="w-20 h-1 bg-gold mx-auto mb-8"></div>
                <p class="text-lg md:text-xl text-gray-300 leading-relaxed mb-8">
                    Youth GBI Philadelphia Life Center adalah komunitas anak muda yang bersemangat untuk bertumbuh dalam iman, 
                    membangun karakter Kristus, dan memberikan dampak positif bagi generasi ini.
                </p>
            </div>
            
            <!-- Core Values -->
            <div class="grid md:grid-cols-3 gap-8 mt-16">
                <div class="value-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="value-icon">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gold">Firman Tuhan</h3>
                    <p class="text-gray-400">Bertumbuh dalam pengenalan akan Firman Tuhan dan hidup sesuai kebenaran-Nya</p>
                </div>
                
                <div class="value-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="value-icon">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gold">Komunitas</h3>
                    <p class="text-gray-400">Membangun persahabatan yang solid dan saling mendukung dalam perjalanan iman</p>
                </div>
                
                <div class="value-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="value-icon">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gold">Pelayanan</h3>
                    <p class="text-gray-400">Melayani Tuhan dengan penuh semangat dan memberikan dampak bagi dunia</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section id="programs" class="py-24 bg-black relative">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="section-title mb-6">
                    Program & <span class="text-gold">Kegiatan</span>
                </h2>
                <div class="w-20 h-1 bg-gold mx-auto mb-8"></div>
                <p class="text-lg text-gray-400 max-w-2xl mx-auto">
                    Berbagai aktivitas yang dirancang untuk membangun iman dan karakter generasi muda
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($programs as $program)
                    <div class="program-card" data-aos="fade-up" data-aos-delay="100">
                        <div class="program-icon">{!! $program->icon !!}</div>
                        <h3 class="text-xl font-bold mb-3">{{ $program->title }}</h3>
                        <p class="text-gray-400 mb-4">{{ $program->	description }}</p>
                        <span class="program-tag">{{ $program->frequency }}</span>
                    </div>
                @endforeach
                
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="gallery" class="py-24 bg-gradient-to-b from-black via-gray-900 to-black">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="section-title mb-6">
                    Galeri <span class="text-gold">Kegiatan</span>
                </h2>
                <div class="w-20 h-1 bg-gold mx-auto mb-8"></div>
                <p class="text-lg text-gray-400 max-w-2xl mx-auto">
                    Momen berharga dan kenangan indah dalam setiap kegiatan Youth Ministry
                </p>
            </div>
            
            <!-- Gallery Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ( $galleries as $galleri )
                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="100">
                        <div class="aspect-square bg-gradient-to-br from-gold to-yellow-600 rounded-lg overflow-hidden relative group cursor-pointer">
                            @php
                                $ext = strtolower(pathinfo($galleri->file_path, PATHINFO_EXTENSION));
                            @endphp
                                @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                                    <img src="{{ asset('storage/'. $galleri->file_path)}}" alt="{{ $galleri->title }}" class="w-full h-full object-cover absolute inset-0" />
                                @elseif(in_array($ext, ['mp4','webm','ogg']))
                                    <video controls class="w-full h-full object-cover absolute inset-0 bg-black">
                                        <source src="{{ asset('storage/'. $galleri->file_path) }}" type="video/{{ $ext }}">
                                        Browser Anda tidak mendukung video.
                                    </video>
                                @endif
                            <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                                <p class="text-sm font-semibold">{{ $galleri->title }}</p>
                            </div>
                        </div>
                    </div>    
                @endforeach
            </div>
        </div>
    </section>

    <!-- Schedule Section -->
    <section id="schedule" class="py-24 bg-black relative overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-gold opacity-5 rounded-full blur-3xl"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="section-title mb-6">
                    Jadwal <span class="text-gold">Kegiatan</span>
                </h2>
                <div class="w-20 h-1 bg-gold mx-auto mb-8"></div>
                <p class="text-lg text-gray-400 max-w-2xl mx-auto">
                    Bergabunglah dengan kami dalam setiap kegiatan yang penuh berkat
                </p>
            </div>
            
            <div class="max-w-4xl mx-auto">
                <!-- Weekly Schedule -->
                <div class="schedule-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="schedule-time">
                            <div class="text-4xl font-bold text-gold">SABTU</div>
                            <div class="text-xl text-gray-400 mt-2">17:00</div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold mb-3">Ibadah Youth Service</h3>
                            <p class="text-gray-400 mb-4">
                                Ibadah khusus untuk generasi muda dengan worship yang energik, pengajaran Firman yang relevan, 
                                dan komunitas yang hangat. Ayo datang dan rasakan hadirat Tuhan bersama-sama!
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center text-sm text-gold">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    GBI Philadelphia Life Center
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Weekday Schedule -->
                <!--
                <div class="schedule-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="schedule-time">
                            <div class="text-4xl font-bold text-gold">RABU</div>
                            <div class="text-xl text-gray-400 mt-2">19:00 - 21:00</div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold mb-3">Small Group Meeting</h3>
                            <p class="text-gray-400 mb-4">
                                Pertemuan kelompok kecil untuk sharing, belajar Firman Tuhan lebih dalam, dan membangun 
                                persahabatan yang bermakna. Lokasi bergantian di rumah anggota.
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center text-sm text-gold">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Lokasi Bervariasi
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                -->
                <!-- Special Events -->
                <!--
                <div class="schedule-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="schedule-time">
                            <div class="text-4xl font-bold text-gold">SABTU</div>
                            <div class="text-xl text-gray-400 mt-2">Bulanan</div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold mb-3">Youth Activities</h3>
                            <p class="text-gray-400 mb-4">
                                Berbagai kegiatan special seperti outbound, social action, creative workshop, dan fun games. 
                                Follow Instagram kami untuk info kegiatan terbaru!
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center text-sm text-gold">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Cek Info Terbaru
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                -->
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="contact" class="py-24 bg-gradient-to-b from-black via-gray-900 to-black relative overflow-hidden">
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full h-full">
            <div class="w-96 h-96 bg-gold opacity-10 rounded-full blur-3xl mx-auto"></div>
        </div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-4xl mx-auto text-center" data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                    Yuk Gabung <span class="text-gold">Yuk</span>
                </h2>
                <p class="text-xl text-gray-300 mb-12 max-w-2xl mx-auto">
                    Kami mengundang Anda untuk menjadi bagian dari keluarga besar Youth GBI Philadelphia Life Center. 
                    Mari bertumbuh dan melayani Tuhan bersama-sama!
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                    <a href="/register" class="cta-button-large">Join Youth Ministry</a>
                    <a href="https://wa.me/6285336618852" class="cta-button-outline">Hubungi Kami</a>
                </div>
                
                <!-- Social Media -->
                <div class="flex justify-center gap-6 mt-12">
                    <a href="https://www.instagram.com/lightyouthandteens?igsh=ejUweG0xZ3I0eGl1" class="social-icon" aria-label="Instagram">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073z"/>
                            <path d="M12 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <!--
                    <a href="#" class="social-icon" aria-label="YouTube">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                    
                    <a href="#" class="social-icon" aria-label="Facebook">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    -->
                    <a href="https://wa.me/6285336618852" class="social-icon" aria-label="WhatsApp">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black border-t border-gray-800 py-12">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <!-- About -->
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <img src="https://customer-assets.emergentagent.com/job_youthgbi-philly/artifacts/bxjvqwqd_logoplc.png" alt="Logo" class="h-10 w-10 object-contain">
                        <div>
                            <h3 class="font-bold text-gold">YOUTH</h3>
                            <p class="text-xs text-gray-400">GBI PLC</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400">
                        Generasi muda yang bersemangat melayani Tuhan dan memberikan dampak bagi dunia.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="font-bold mb-4 text-gold">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#home" class="text-gray-400 hover:text-gold transition-colors">Home</a></li>
                        <li><a href="#about" class="text-gray-400 hover:text-gold transition-colors">Tentang Kami</a></li>
                        <li><a href="#programs" class="text-gray-400 hover:text-gold transition-colors">Program</a></li>
                        <li><a href="#gallery" class="text-gray-400 hover:text-gold transition-colors">Galeri</a></li>
                        <li><a href="#schedule" class="text-gray-400 hover:text-gold transition-colors">Jadwal</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h4 class="font-bold mb-4 text-gold">Kontak</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>GBI Philadelphia Life Center Jl. Babarsari No.45, Janti, Caturtunggal, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281</span>
                        </li>
                        <!--
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>youth@gbiplc.org</span>
                        </li>
                        -->
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span>+62 853-3661-8852</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Schedule -->
                <div>
                    <h4 class="font-bold mb-4 text-gold">Jadwal Ibadah</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>
                            <span class="font-semibold text-white">Sabtu</span><br>
                            17:00 WIB
                        </li>
                        <!--
                        <li>
                            <span class="font-semibold text-white">Rabu</span><br>
                            19:00 - 21:00 WIB<br>
                            <span class="text-xs">(Small Group)</span>
                        </li>
                        -->
                    </ul>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="border-t border-gray-800 pt-8 text-center text-sm text-gray-400">
                <p>&copy; 2025 Youth GBI Philadelphia Life Center. All rights reserved.</p>
                <p class="mt-2">"Turn Many to Righteousness" - Daniel 12:3</p>
            </div>
        </div>
    </footer>

    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Custom JavaScript -->
    <!-- <script src="assets/js/script.js"></script> -->
    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>
