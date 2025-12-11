<x-nav-bar />
<x-layout>

    <div class="flex min-h-full flex-col justify-center px-4 py-12 lg:px-8" style="margin-top:40px; border-radius: 16px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(6px);">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-black">Reset/Buat Password</h2>
        <p class="mt-2 text-center text-sm text-gray-800">
            Masukkan alamat email Anda, dan kami akan mengirimkan tautan untuk mereset kata sandi Anda.
        </p>
    </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6" action="{{ route('password.email') }}" method="POST">
            @csrf

            <!-- Session Status -->
            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('status') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                There {{ $errors->count() > 1 ? 'were' : 'was' }} {{ $errors->count() }} error{{ $errors->count() > 1 ? 's' : '' }} with your submission
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul role="list" class="list-disc space-y-1 pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div>
                <label for="email" class="block text-sm/6 font-semibold text-black">Email address</label>
                <div class="mt-2">
                <input type="email" name="email" id="email" autocomplete="email" required
                       value="{{ old('email') }}"
                       class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-[#723322] sm:text-sm/6">
                </div>
            </div>
            <div>
                <button type="submit" class="flex w-full justify-center rounded-md bg-blue-700 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#723322]">Send Password Reset Link</button>
            </div>
            </form>

            <p class="mt-10 text-center text-sm/6 text-gray-800">
                Remember your password?
                <a href="{{ route('login') }}" class="font-semibold text-blue-400 hover:text-blue-300">Sign in here</a>
            </p>
        </div>

    </div>
</x-layout>
