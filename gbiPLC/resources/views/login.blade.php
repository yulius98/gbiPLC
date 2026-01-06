<x-layout>
<div class="flex min-h-full flex-col justify-center px-4 py-12 lg:px-8 " style="margin-top:40px; border-radius: 16px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(6px);">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <img class="mx-auto h-40 w-auto rounded-full" src="..\logoplc.png" alt="Your Company">
        <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight" style="font-family: 'Dancing Script', cursive;">
            PLC Makin Asik
        </h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm ">
        <form class="space-y-6" action="{{ route('login.post') }}" method="POST">
        @csrf
            @if ($errors->any())
                <div class="alert alert-danger text-red-600 mb-4 p-3 rounded-md bg-red-50 border border-red-200">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error )
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('status'))
                <div class="alert alert-success text-green-100 mb-4 p-3 rounded-md bg-green-50 border border-green-200">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('message'))
                <div class="alert alert-info text-blue-100 mb-4 p-3 rounded-md bg-blue-50 border border-blue-200">
                    {{ session('message') }}
                </div>
            @endif
        <div>
            <label for="email" class="block text-sm/6 font-semibold">Email</label>
            <div class="mt-2">
            <input type="email" name="email" id="email" autocomplete="email" required value="{{ old('email') }}" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[#723322] sm:text-sm/6" placeholder="Masukkan email Anda">
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm/6 font-semibold">Password</label>
               <div class="text-sm">
                    <a href="/forgot-password" class="font-semibold hover:text-blue-400">Lupa/Buat password?</a>
                </div>
            </div>
            <div class="mt-2">
            <input type="password" name="password" id="password" autocomplete="current-password" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[#723322] sm:text-sm/6" placeholder="Masukkan password Anda">
            </div>
        </div>

        <div>
            <div class="flex gap-3">
                <a href="{{ route('home') }}" class="flex flex-1 justify-center rounded-md bg-gray-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-gray-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600 transition duration-200">
                    <span class="flex items-center">
                        <svg class="-ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back
                    </span>
                </a>
                <button type="submit" class="flex flex-1 justify-center rounded-md bg-[#3a3938] px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-white hover:text-black focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white transition duration-200">
                    <span class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white hidden" id="loading-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span id="button-text">Login</span>
                    </span>
                </button>
            </div>
        </div>
        </form>




    </div>

</div>

<script>
    // Loading state for form submission
    document.querySelector('form').addEventListener('submit', function() {
        const button = document.querySelector('button[type="submit"]');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('loading-spinner');

        button.disabled = true;
        buttonText.textContent = 'Sedang masuk...';
        spinner.classList.remove('hidden');
    });

    // Auto hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            }, 5000);
        });
    });
</script>
</x-layout>
