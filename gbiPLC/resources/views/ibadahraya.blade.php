<x-nav-bar/>
<x-layout>

<style>
  @keyframes neonPulse {
    0%, 100% {
      box-shadow: 0 0 8px #ffffff, 0 0 16px #ffffff, 0 0 24px #ffffff;
    }
    50% {
      box-shadow: 0 0 12px #ffffff, 0 0 20px #ffffff, 0 0 32px #ffffff;
    }
  }

  .neon-glow {
    animation: neonPulse 2s infinite;
    border: 1px solid white;
    background-color: #000;
    color: white;
  }

  .neon-glow:focus {
    outline: none;
    animation: neonPulse 1.5s infinite;
  }

  .neon-button {
    /*animation: neonPulse 2s infinite; */
    background-color: black; /* Tailwind blue-800 */
    border: 1px solid white;
    color: white;
  }

  .neon-button:hover {
    background-color: black; /* Tailwind blue-600 */
    animation: neonPulse 1s infinite;
  }

  /* Additional styles for camera capture */
  #video {
    width: 100%;
    max-width: 320px;
    border: 2px solid white;
    border-radius: 8px;
  }

  #canvas {
    display: none;
    width: 100%;
    max-width: 320px;
    border: 2px solid white;
    border-radius: 8px;
  }

  #gallery-preview {
    display: none;
    width: 100%;
    max-width: 320px;
    border: 2px solid white;
    border-radius: 8px;
    object-fit: cover;
  }

  #camera-controls {
    margin-top: 10px;
  }

  #camera-controls button {
    margin-right: 10px;
  }

  /* Fix untuk Date Input */
  input[type="date"] {
    position: relative;
    background-color: #1a1a1a !important;
    color: white !important;
    color-scheme: dark;
  }

  input[type="date"]::-webkit-calendar-picker-indicator {
    cursor: pointer;
    filter: invert(1);
    opacity: 0.8;
  }

  input[type="date"]::-webkit-calendar-picker-indicator:hover {
    opacity: 1;
  }

  /* Untuk Firefox */
  input[type="date"]::-moz-calendar-picker-indicator {
    cursor: pointer;
    filter: invert(1);
    opacity: 0.8;
  }
</style>

<div class="flex flex-col justify-center items-center text-white pt-16 shadow-lg shadow-gray rounded-5" style="border-radius: 16px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px);">
    {{-- Error Message --}}
    @if ($errors->any())
        <div class="pt-3 w-full max-w-xl">
            <div class="alert alert-danger bg-red-600 text-white p-4 rounded shadow">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if ($errors->has('filename'))
        <div class="text-red-500 text-sm mt-1">
            {{ $errors->first('filename') }}
        </div>
    @endif

    {{-- Success Message --}}
    @if (session()->has('message'))
        <div class="pt-3 w-full max-w-xl">
            <div id="flash-message" class="bg-green-600 text-white p-4 rounded shadow">
                {{ session('message') }}
            </div>
        </div>
    @endif

    <div class="py-24 sm:py-32">
        <div class="mx-auto grid max-w-7xl gap-20 px-6 lg:px-8 xl:grid-cols-2">
            <div class="max-w-xl">
                <h2 class="text-3xl font-semibold tracking-tight text-pretty text-black sm:text-4xl">Ibadah Raya GBI Philadelphia Life Center</h2>
            </div>
            <form id="ibadahraya" action="{{ route('ibadah-raya.getlink') }}" method="get" class="space-y-6" enctype="multipart/form-data" onsubmit="return preparePhotoForSubmit()">
            @csrf
                <div>
                    <label for="tgl_ibadah" class="block mb-1 text-black">Tanggal Ibadah</label>
                    <input type="date" id="tgl_ibadah" name="tgl_ibadah"
                        class="form-control w-full px-4 py-2 border rounded shadow focus:outline-none focus:ring-2 focus:ring-white transition {{ $errors->has('tgl_ibadah') ? 'border-red-500' : 'border-white' }}"
                        value="{{ old('tgl_ibadah', request('tgl_ibadah')) }}"
                        style="background-color: #f3eeee; color: white; color-scheme: dark;" />
                    @error('tgl_ibadah')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="ibadah_ke" class="block mb-1 text-black">Ibadah ke..</label>
                    <select id="ibadah_ke" name="ibadah_ke"
                        class="form-control w-full px-4 py-2 bg-black border rounded shadow focus:outline-none focus:ring-2 focus:ring-white transition {{ $errors->has('ibadah_ke') ? 'border-red-500' : 'border-white' }} text-white">
                        <option value="">-- Pilih Ibadah --</option>
                        <option value="Ibadah 1" {{ old('ibadah_ke', request('ibadah_ke')) == '1' ? 'selected' : '' }}>Ibadah 1</option>
                        <option value="Ibadah 2" {{ old('ibadah_ke', request('ibadah_ke')) == '2' ? 'selected' : '' }}>Ibadah 2</option>
                    </select>
                    @error('ibadah_ke')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <!-- Tombol -->
                <div class="mt-6">
                    <button type="submit"
                        class="ml-2 px-4 py-2 rounded neon-button transition">
                        CARI
                    </button>
                </div>
            </form>

            @if(isset($ibadahRaya) && $ibadahRaya)
            <div class="col-span-2">
                <div class="bg-gray-900 p-6 rounded-lg shadow-lg border border-white">
                    <h3 class="text-2xl font-bold text-white mb-4">Hasil Pencarian</h3>
                    <div class="mb-4">
                        <p class="text-white"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($ibadahRaya->tgl_ibadah)->format('d F Y') }}</p>
                        <p class="text-white"><strong>{{ $ibadahRaya->ibadah_ke }}</p>
                    </div>

                    @if($ibadahRaya->link_ibadah)
                    <div class="mb-4">
                        <p class="text-white mb-2"><strong>Link Ibadah:</strong></p>
                        <a href="{{ $ibadahRaya->link_ibadah }}" target="_blank" class="text-blue-400 hover:text-blue-300 underline break-all">
                            {{ $ibadahRaya->link_ibadah }}
                        </a>
                    </div>

                    @php
                        // Extract YouTube video ID from various YouTube URL formats
                        $youtubeId = null;
                        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|live\/|embed\/)|youtu\.be\/)([^&\?\/]+)/', $ibadahRaya->link_ibadah, $matches)) {
                            $youtubeId = $matches[1];
                        }
                    @endphp

                    @if($youtubeId)
                    <div class="mt-6 border border-white p-4 rounded-lg">
                        <h4 class="text-xl font-semibold text-white mb-3">📹 Video Ibadah</h4>
                        <div class="relative bg-black" style="padding-bottom: 56.25%; height: 0; overflow: hidden;">
                            <iframe
                                class="absolute top-0 left-0 w-full h-full rounded-lg"
                                src="https://www.youtube.com/embed/{{ $youtubeId }}"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                    @else
                    <div class="mt-6 bg-yellow-900 border border-yellow-600 p-4 rounded-lg">
                        <p class="text-yellow-200">⚠️ Link yang disimpan bukan format YouTube yang valid.</p>
                        <p class="text-yellow-200 text-sm mt-2">Format yang didukung: youtube.com/watch?v=xxx atau youtu.be/xxx</p>
                    </div>
                    @endif
                    @else
                    <div class="text-yellow-400">
                        <p>Link ibadah belum tersedia.</p>
                    </div>
                    @endif
                </div>
            </div>
            @elseif(request('tgl_ibadah') && request('ibadah_ke'))
            <div class="col-span-2">
                <div class="bg-gray-900 p-6 rounded-lg shadow-lg border border-white">
                    <p class="text-red-400 text-lg">Data tidak ditemukan untuk tanggal dan ibadah yang dipilih.</p>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

<script>
    // Pastikan date input berfungsi dengan baik
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('tgl_ibadah');

        // Set max date ke hari ini
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('max', today);

        // Jika ada error atau data tidak ditemukan, fokus ke input
        @if(request('tgl_ibadah') && request('ibadah_ke') && !isset($ibadahRaya))
            dateInput.focus();
        @endif
    });
</script>

</x-layout>
