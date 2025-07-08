<x-nav-bar-daftar/>
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
</style>

<div class="flex flex-col justify-center items-center bg-black text-white">
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

    <div class="w-full max-w-3xl p-20 rounded-lg" style="background: rgba(0,0,0,0.8);">
        <img class="mx-auto h-56 w-auto rounded-full mb-6" src="..\logoplc.png" alt="Your Company">
        <section class="hero" id="home">
            <div class="mask-container">
            <main class="content">
                <h1 class="text-4xl text-purple-300 text-center font-serif font-normal mb-2" style="text-shadow: 0 0 5px #D8B4FE, 0 0 10px #D8B4FE, 0 0 20px #A855F7;">
                    WELCOME
                </h1>
                <marquee behavior="scroll" direction="left" scrollamount="5">
                    <p class="text-white mix-blend-difference font-serif font-normal text-justify text-base">
                        Dan orang-orang bijaksana akan bercahaya seperti cahaya cakrawala dan yang telah menuntun banyak orang kepada kebenaran seperti bintang-bintang tetap untuk selama-lamanya 
                        <strong class="text-white shadow-[0_0_20px_white]">-- Daniel 12 : 3 --</strong>
                    </p>
                </marquee>
            </main>
            </div>
        </section>
    </div>
    <!-- START FORM -->
    <!-- <div class="my-6 p-6 rounded neon-glow transition"> -->
    <div class=" w-full max-w-3xl p-6 rounded-2xl neon-glow transition">
        <form id = "daftar"  action="/Daftar" method="post" class="space-y-6" enctype="multipart/form-data">
            @csrf
            <div class=" w-full max-3-3xl grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri -->
                <div class="space-y-4">
                    {{-- Nama --}}
                    <div>
                        <label for="name" class="block mb-1 text-white">Nama</label>
                        <input type="text" id="name" name="name"
                            class=" w-full px-4 py-2 bg-black border border-white text-white rounded shadow focus:outline-none focus:ring-2 focus:ring-white transition" />
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label for="tgl_lahir" class="block mb-1 text-white">Tanggal Lahir</label>
                        <input type="date" id="tgl_lahir" name="tgl_lahir" 
                            class="form-control w-full px-4 py-2 bg-black border border-white text-white rounded shadow focus:outline-none focus:ring-2 focus:ring-white transition" />
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label for="no_HP" class="block mb-1 text-white">No Telp/HP</label>
                        <input type="text" id="no_HP" name="no_HP"
                            class="w-full px-4 py-2 bg-black border border-white text-white rounded shadow focus:outline-none focus:ring-2 focus:ring-white transition" />
                    </div>

                    {{-- Golongan Darah --}}
                    <div>
                        <label for="gol_darah" class="block mb-1 text-white">Golongan Darah</label>
                        <select id="gol_darah" name="gol_darah"
                            class="w-full px-4 py-2 bg-black border border-white text-white rounded shadow focus:outline-none focus:ring-2 focus:ring-white transition">
                            <option value="">-- Pilih Golongan Darah --</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O">O</option>
                        </select>
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label for="alamat" class="block mb-1 text-white">Alamat</label>
                        <input type="text" id="alamat" name="alamat"
                            class="w-full px-4 py-2 bg-black border border-white text-white rounded shadow focus:outline-none focus:ring-2 focus:ring-white transition" />
                    </div>

                    {{-- Foto --}}
                    <div>
                        <label for="foto" class="block mb-1 text-white">Foto</label>
                        <input type="file" id="filename" name="filename"
                            class="form-control w-full px-4 py-2 bg-gray-600 border border-white text-white rounded shadow focus:outline-none focus:ring-2 focus:ring-white transition" />
                    </div>
                    
                </div>
            </div>

            <!-- Tombol -->
            <div class="mt-6">
                <a href="/" class="inline-block px-4 py-2 text-sm font-medium text-white border border-white rounded hover:bg-white hover:text-black transition">
                    ← Kembali
                </a>
                <button type="submit" 
                    class="ml-2 px-4 py-2 rounded neon-button transition">
                    SIMPAN
                </button>
            </div>
        </form>
    </div>
    <!-- AKHIR FORM -->
</div>
</x-layout>

<script>
    // Function to check if the image input is empty
    function validateForm() {
        const imageInput = document.getElementById('foto');
        
        if (imageInput.files.length === 0) {
            alert('Silakan pilih foto');
        } else {
            // Submit the form if the image is selected
            
            document.getElementById('daftar').submit();
        }
    }
</script>

<script>
    function previewImage(event) {
        const imagePreview = document.getElementById('image-preview');
        imagePreview.src = URL.createObjectURL(event.target.files[0]);
        imagePreview.classList.remove('hidden');
    }
</script>
    
