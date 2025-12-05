<div class="mt-16 pt-10 pb-8 w-full max-w-5xl mx-auto shadow-lg shadow-black p-6" style="border-radius: 16px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px);">
    <h2 class="text-3xl md:text-4xl font-bold text-center text-purple-800 mb-8 tracking-wide">Materi Kotbah</h2>

    @if (session()->has('error'))
        <div class="alert alert-danger mb-4 text-center">
            {{ session('error') }}
        </div>
    @endif

    @if (!empty($availableDates))
        <!-- Pilihan Tanggal Kotbah -->
        <div class="mb-8">
            <label for="tanggal-kotbah" class="block text-xl font-semibold text-purple-800 mb-4">Tanggal Kotbah</label>
            <div class="relative w-full md:w-2/3 lg:w-1/2">
                <select wire:model.live="selectedDate" id="tanggal-kotbah"
                        class="w-full p-3 rounded-lg bg-gray-800 border border-purple-800 text-white focus:outline-none focus:ring-2 focus:ring-purple-400 appearance-none pr-10">
                    <option value="">Pilih Tanggal Kotbah</option>
                    @foreach ($availableDates as $date)
                        <option value="{{ $date['date'] }}">{{ $date['label'] }}</option>
                    @endforeach
                </select>
                <!-- Custom dropdown arrow -->
                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                    <svg class="w-4 h-4 text-purple-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div wire:loading wire:target="selectedDate" class="mb-6">
            <div class="flex items-center justify-center p-4 bg-gray-800 rounded-lg border border-purple-300">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-purple-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-purple-200">Memuat data kotbah...</span>
            </div>
        </div>

        @if ($selectedDate && !$selectedKotbah)
            <!-- Pesan ketika tanggal dipilih tapi tidak ada data -->
            <div wire:loading.remove wire:target="selectedDate" class="text-center p-8 bg-gray-800 rounded-lg border border-gray-600">
                <svg class="w-16 h-16 mx-auto text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-400 text-lg">Data kotbah tidak ditemukan untuk tanggal yang dipilih.</p>
            </div>
        @elseif ($selectedKotbah)
            <!-- Judul Kotbah -->
            <div wire:loading.remove wire:target="selectedDate" class="mb-6">
                <label class="block text-xl font-semibold text-purple-800 mb-2">Judul Kotbah</label>
                <div class="p-4 bg-gray-800 rounded-lg border border-purple-300">
                    <h3 class="text-lg text-white">{{ $selectedKotbah->judul ?: 'Materi Kotbah' }}</h3>
                    <p class="text-gray-300 text-sm mt-2">
                        Tanggal: {{ \Carbon\Carbon::parse($selectedKotbah->tgl_kotbah)->format('d F Y') }}
                    </p>
                </div>
            </div>

            <!-- Preview File -->
            <div wire:loading.remove wire:target="selectedDate">
            @if ($selectedKotbah->filename)
                <div class="mb-6">
                    <label class="block text-xl font-semibold text-purple-800 mb-4">File Kotbah</label>
                    <div class="bg-gray-800 rounded-lg border border-purple-300 p-6">
                        @php
                            $fileExtension = pathinfo($selectedKotbah->filename, PATHINFO_EXTENSION);
                            $fileName = basename($selectedKotbah->filename);
                            $filePath = storage_path('app/public/' . $selectedKotbah->filename);
                            $fileSize = file_exists($filePath) ? $this->formatBytes(filesize($filePath)) : 'N/A';
                        @endphp

                        @if (strtolower($fileExtension) === 'pdf')
                            <!-- PDF Preview -->
                            <div class="text-center">
                                <div class="mb-4">
                                    <svg class="w-16 h-16 mx-auto text-red-500 mb-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                    </svg>
                                    <p class="text-white font-medium">File PDF</p>
                                    <p class="text-gray-300 text-sm">{{ $fileName }}</p>
                                    <p class="text-gray-400 text-xs">Ukuran: {{ $fileSize }}</p>
                                </div>
                                <!-- Embed PDF jika memungkinkan -->
                                <div class="w-full h-96 border border-gray-600 rounded-lg overflow-hidden">
                                    <embed src="{{ asset('storage/' . $selectedKotbah->filename) }}"
                                           type="application/pdf"
                                           width="100%"
                                           height="100%"
                                           class="rounded-lg" />
                                </div>
                            </div>
                        @elseif (in_array(strtolower($fileExtension), ['ppt', 'pptx']))
                            <!-- PowerPoint Preview -->
                            <div class="text-center">
                                <div class="mb-4">
                                    <svg class="w-16 h-16 mx-auto text-orange-500 mb-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm6 10a1 1 0 01-1-1V9a1 1 0 011-1h1a2 2 0 110 4h-1v1a1 1 0 01-1 1zm0-3V9h1a1 1 0 110 2h-1z" clip-rule="evenodd" />
                                    </svg>
                                    <p class="text-white font-medium">File PowerPoint</p>
                                    <p class="text-gray-300 text-sm">{{ $fileName }}</p>
                                    <p class="text-gray-400 text-xs">Ukuran: {{ $fileSize }}</p>
                                </div>
                                <div class="bg-gray-700 p-8 rounded-lg">
                                    <p class="text-gray-300">Preview PowerPoint tidak tersedia di browser.</p>
                                    <p class="text-gray-400 text-sm mt-2">Silakan download file untuk melihat isinya.</p>
                                </div>
                            </div>
                        @else
                            <!-- File Type Lainnya -->
                            <div class="text-center">
                                <div class="mb-4">
                                    <svg class="w-16 h-16 mx-auto text-gray-500 mb-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                    </svg>
                                    <p class="text-white font-medium">File Kotbah</p>
                                    <p class="text-gray-300 text-sm">{{ $fileName }}</p>
                                    <p class="text-gray-400 text-xs">Ukuran: {{ $fileSize }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tombol Download -->
                <div class="text-center">
                    <a href="{{ route('materi-kotbah.download', $selectedKotbah->id) }}"
                       class="inline-flex items-center px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition duration-300 ease-in-out transform hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download File
                    </a>
                </div>
            @else
                <div class="text-center p-8 bg-gray-800 rounded-lg border border-gray-600">
                    <p class="text-gray-400">File kotbah tidak tersedia untuk tanggal ini.</p>
                </div>
            @endif
            </div>
        @endif
    @else
        <div class="text-center p-8">
            <p class="text-gray-400 text-lg">Belum ada materi kotbah yang tersedia.</p>
        </div>
    @endif
</div>
