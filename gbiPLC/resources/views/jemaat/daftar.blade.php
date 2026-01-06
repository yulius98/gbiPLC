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
</style>

<div class="flex flex-col justify-center items-center bg-black text-white pt-16">
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
    <div class=" w-full max-w-3xl p-8 rounded-2xl neon-glow transition">
        <form id="daftar" action="/Daftar" method="post" class="space-y-6" enctype="multipart/form-data" onsubmit="return preparePhotoForSubmit()">
            @csrf
            <div class=" w-full max-3-3xl grid grid-cols-1 md:grid-cols-1 gap-6">
                <!-- Kolom Kiri -->
                <div class="space-y-4">
                    {{-- Nama --}}
                    <div>
                        <label for="name" class="block mb-1 text-white">Nama</label>
                        <input type="text" id="name" name="name"
                            class="w-full px-4 py-2 bg-black border rounded shadow focus:outline-none focus:ring-2 focus:ring-white transition {{ $errors->has('name') ? 'border-red-500' : 'border-white' }} text-white" value="{{ old('name') }}" />
                        @error('name')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label for="tgl_lahir" class="block mb-1 text-white">Tanggal Lahir</label>
                        <input type="date" id="tgl_lahir" name="tgl_lahir"
                            class="form-control w-full px-4 py-2 bg-black border rounded shadow focus:outline-none focus:ring-2 focus:ring-white transition {{ $errors->has('tgl_lahir') ? 'border-red-500' : 'border-white' }} text-white" value="{{ old('tgl_lahir') }}" />
                        @error('tgl_lahir')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label for="alamat" class="block mb-1 text-white">Alamat</label>
                        <input type="text" id="alamat" name="alamat"
                            class="w-full px-4 py-2 bg-black border rounded shadow focus:outline-none focus:ring-2 focus:ring-white transition {{ $errors->has('alamat') ? 'border-red-500' : 'border-white' }} text-white" value="{{ old('alamat') }}" />
                        @error('alamat')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block mb-1 text-white">Email</label>
                        <input type="email" id="email" name="email"
                            class="w-full px-4 py-2 bg-black border rounded shadow focus:outline-none focus:ring-2 focus:ring-white transition {{ $errors->has('email') ? 'border-red-500' : 'border-white' }} text-white" value="{{ old('email') }}" />
                        @error('email')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label for="no_HP" class="block mb-1 text-white">No Telp/HP</label>
                        <input type="text" id="no_HP" name="no_HP"
                            class="w-full px-4 py-2 bg-black border rounded shadow focus:outline-none focus:ring-2 focus:ring-white transition {{ $errors->has('no_HP') ? 'border-red-500' : 'border-white' }} text-white" value="{{ old('no_HP') }}" />
                        @error('no_HP')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Golongan Darah --}}
                    <div>
                        <label for="gol_darah" class="block mb-1 text-white">Golongan Darah</label>
                        <select id="gol_darah" name="gol_darah"
                            class="w-full px-4 py-2 bg-black border rounded shadow focus:outline-none focus:ring-2 focus:ring-white transition {{ $errors->has('gol_darah') ? 'border-red-500' : 'border-white' }} text-white">
                            <option value="">-- Pilih Golongan Darah --</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O">O</option>
                        </select>
                        @error('gol_darah')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>



                    {{-- Alamat Facebook --}}
                    <div>
                        <label for="facebook" class="block mb-1 text-white">Facebook</label>
                        <input type="text" id="facebook" name="facebook"
                            class="w-full px-4 py-2 bg-black border rounded shadow focus:outline-none focus:ring-2 focus:ring-white transition {{ $errors->has('facebook') ? 'border-red-500' : 'border-white' }} text-white" value="{{ old('facebook') }}" />
                        @error('facebook')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Alamat Instagram --}}
                    <div>
                        <label for="instagram" class="block mb-1 text-white">Instagram</label>
                        <input type="text" id="instagram" name="instagram"
                            class="w-full px-4 py-2 bg-black border rounded shadow focus:outline-none focus:ring-2 focus:ring-white transition {{ $errors->has('instagram') ? 'border-red-500' : 'border-white' }} text-white" value="{{ old('instagram') }}" />
                        @error('instagram')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Foto --}}
                    <div>
                        <label for="foto" class="block mb-1 text-white">Foto</label>
                        <!-- File input for camera capture -->
                        <input type="file" id="filename" name="filename" accept="image/*" capture="user" style="display:none;" />
                        <!-- File input for gallery selection -->
                        <input type="file" id="gallery-input" accept="image/*" style="display:none;" />
                        <!-- Video preview for live camera -->
                        <video id="video" autoplay playsinline class="w-full max-w-xs md:max-w-sm rounded border-2 border-white mb-2"></video>
                        <!-- Canvas to capture photo -->
                        <canvas id="canvas" class="w-full max-w-xs md:max-w-sm rounded border-2 border-white mb-2" style="display:none;"></canvas>
                        <!-- Preview image from gallery -->
                        <img id="gallery-preview" class="w-full max-w-xs md:max-w-sm rounded border-2 border-white mb-2" style="display:none;" />
                        <!-- Camera control buttons -->
                        <div id="camera-controls">
                            <button type="button" id="gallery-action" class="neon-button px-4 py-2 rounded">Foto dari Galeri</button>
                            <button type="button" id="camera-action" class="neon-button px-4 py-2 rounded" data-action="start">Foto dari Kamera</button>
                            <button type="button" id="retake-photo" class="neon-button px-4 py-2 rounded" disabled>Ulangi Foto</button>
                        </div>
                        @error('filename')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
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
    // Camera capture script
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const cameraActionButton = document.getElementById('camera-action');
    const galleryActionButton = document.getElementById('gallery-action');
    const retakePhotoButton = document.getElementById('retake-photo');
    const filenameInput = document.getElementById('filename');
    const galleryInput = document.getElementById('gallery-input');
    const galleryPreview = document.getElementById('gallery-preview');
    let stream;

    // Camera action button functionality
    cameraActionButton.addEventListener('click', async () => {
        const action = cameraActionButton.getAttribute('data-action');

        if (action === 'start') {
            // Start camera functionality
            try {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    alert('Browser Anda tidak mendukung akses kamera.');
                    return;
                }
                stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
                video.srcObject = stream;

                // Change button to capture mode
                cameraActionButton.setAttribute('data-action', 'capture');
                cameraActionButton.textContent = 'Ambil Foto';
                galleryActionButton.disabled = true;
                retakePhotoButton.disabled = true;
                canvas.style.display = 'none';
                galleryPreview.style.display = 'none';
                video.style.display = 'block';
                // Clear previous file input value
                filenameInput.value = '';
            } catch (err) {
                alert('Tidak dapat mengakses kamera: ' + err.message);
            }
        } else if (action === 'capture') {
            // Capture photo functionality
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            canvas.style.display = 'block';
            video.style.display = 'none';
            galleryPreview.style.display = 'none';

            // Change button to disabled state and enable retake
            cameraActionButton.disabled = true;
            galleryActionButton.disabled = true;
            retakePhotoButton.disabled = false;

            // Stop the camera stream
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }

            // Resize and compress image to be under 1MB
            const compressedBlob = await resizeAndCompressImage(canvas);
            const file = new File([compressedBlob], 'captured_photo.jpg', { type: 'image/jpeg' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            filenameInput.files = dataTransfer.files;
        }
    });

    // Gallery action button functionality
    galleryActionButton.addEventListener('click', () => {
        galleryInput.click();
    });

    // Gallery input change event
    galleryInput.addEventListener('change', async (event) => {
        const file = event.target.files[0];
        if (file) {
            // Validate file size (max 5MB for initial upload)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 5MB.');
                return;
            }

            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('File harus berupa gambar.');
                return;
            }

            // Display preview
            const reader = new FileReader();
            reader.onload = async (e) => {
                galleryPreview.src = e.target.result;
                galleryPreview.style.display = 'block';
                video.style.display = 'none';
                canvas.style.display = 'none';

                // Compress image if needed
                const img = new Image();
                img.onload = async () => {
                    const tempCanvas = document.createElement('canvas');
                    const ctx = tempCanvas.getContext('2d');
                    tempCanvas.width = img.width;
                    tempCanvas.height = img.height;
                    ctx.drawImage(img, 0, 0);

                    const compressedBlob = await resizeAndCompressImage(tempCanvas);
                    const compressedFile = new File([compressedBlob], file.name, { type: 'image/jpeg' });

                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(compressedFile);
                    filenameInput.files = dataTransfer.files;
                };
                img.src = e.target.result;

                // Update button states
                cameraActionButton.disabled = true;
                galleryActionButton.disabled = true;
                retakePhotoButton.disabled = false;
            };
            reader.readAsDataURL(file);
        }
    });

    // Helper function to resize and compress image to be under 1MB
    async function resizeAndCompressImage(canvas) {
        const MAX_SIZE = 1024; // max width or height in pixels
        const MAX_FILE_SIZE = 1 * 1024 * 1024; // 1MB in bytes
        let width = canvas.width;
        let height = canvas.height;

        // Calculate new dimensions while maintaining aspect ratio
        if (width > height && width > MAX_SIZE) {
            height = Math.round(height * (MAX_SIZE / width));
            width = MAX_SIZE;
        } else if (height > width && height > MAX_SIZE) {
            width = Math.round(width * (MAX_SIZE / height));
            height = MAX_SIZE;
        } else if (width === height && width > MAX_SIZE) {
            width = MAX_SIZE;
            height = MAX_SIZE;
        }

        // Create an offscreen canvas for resizing
        const offscreenCanvas = document.createElement('canvas');
        offscreenCanvas.width = width;
        offscreenCanvas.height = height;
        const ctx = offscreenCanvas.getContext('2d');
        ctx.drawImage(canvas, 0, 0, width, height);

        // Compress image by adjusting quality until under 1MB
        let quality = 0.9;
        let blob = await new Promise(resolve => offscreenCanvas.toBlob(resolve, 'image/jpeg', quality));
        while (blob.size > MAX_FILE_SIZE && quality > 0.1) {
            quality -= 0.1;
            blob = await new Promise(resolve => offscreenCanvas.toBlob(resolve, 'image/jpeg', quality));
        }
        return blob;
    }

    // Retake photo functionality
    retakePhotoButton.addEventListener('click', () => {
        // Reset to initial state
        cameraActionButton.setAttribute('data-action', 'start');
        cameraActionButton.textContent = 'Mulai Kamera';
        cameraActionButton.disabled = false;
        galleryActionButton.disabled = false;
        retakePhotoButton.disabled = true;

        // Hide all previews
        canvas.style.display = 'none';
        video.style.display = 'block';
        galleryPreview.style.display = 'none';

        // Clear file inputs
        filenameInput.value = '';
        galleryInput.value = '';
    });

    function preparePhotoForSubmit() {
        if (!filenameInput.files.length) {
            alert('Silakan ambil foto dari kamera atau pilih dari galeri terlebih dahulu.');
            return false;
        }
        return true;
    }
</script>

