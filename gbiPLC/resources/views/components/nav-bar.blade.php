<nav class="fixed w-full top-0 z-50 shadow-[0_0_10px_white] bg-black transition-all duration-300 "
  style="background-image: url('{{ asset('BGNavBar.png') }}'); background-size: cover; background-position: center;"
  x-data="{ isOpen: false, dropdownOpen: false }">
{{-- <nav class="fixed w-full top-0 z-50 shadow-[0_0_10px_white] bg-black transition-all duration-300 "
  x-data="{ isOpen: false, dropdownOpen: false }"> --}}
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <div class="flex items-center">
          <div class="shrink-0 flex items-center">
            <img class="h-14 w-auto rounded-full shadow-md object-cover my-1 hover:opacity-90" src="{{ asset('logoplc.png') }}" alt="GBI PLC">
            <h2 class="text-white text-lg font-semibold ml-3">GBI PLC</h2>
          </div>

          <div class="hidden md:block">
            <div class="ml-10 flex items-baseline space-x-4">
              <a href="{{ route('home') }}" class="rounded-md px-3 py-2 text-sm font-medium text-white border border-transparent hover:border-white hover:shadow-[0_0_10px_white] hover:bg-black hover:text-white transition duration-300">Home</a>

              <!-- Dropdown Menu untuk Ibadah Raya -->
              <x-dropdown-menu label="Ibadah Raya">
                <x-dropdown-item href="{{ route('materi-kotbah') }}">Materi Kotbah</x-dropdown-item>
                <x-dropdown-item href="{{ route('ibadah-raya') }}">Ibadah Raya</x-dropdown-item>
              </x-dropdown-menu>

              <!-- Dropdown Menu untuk Komsel -->
              <x-dropdown-menu label="Life Group">
                <x-dropdown-item href="{{ route('materi-komsel') }}">Materi Life Group</x-dropdown-item>
                <x-dropdown-item href="{{ route('list-komsel') }}">Daftar Life Group</x-dropdown-item>
              </x-dropdown-menu>


              <a href="{{ route('youth') }}" class="rounded-md px-3 py-2 text-sm font-medium text-white border border-transparent hover:border-white hover:shadow-[0_0_10px_white] hover:bg-black hover:text-white transition duration-300">Youth</a>
              <a href="{{ route('home') }}#event" class="rounded-md px-3 py-2 text-sm font-medium text-white border border-transparent hover:border-white hover:shadow-[0_0_10px_white] hover:bg-black hover:text-white transition duration-300">Event</a>
              @auth
                <a href="{{ route('home') }}#birthday" class="rounded-md px-3 py-2 text-sm font-medium text-white border border-transparent hover:border-white hover:shadow-[0_0_10px_white] hover:bg-black hover:text-white transition duration-300">Birthday</a>
              @endauth
            </div>
          </div>
        </div>
        <div class="hidden mr-2 md:block">
          <div class="ml-4 flex items-center md:ml-6">
              @guest
                  <a href="{{ route('register') }}" class="rounded-md px-3 py-2 text-sm font-medium text-white border border-transparent hover:border-white hover:shadow-[0_0_10px_white] hover:bg-black hover:text-white transition duration-300">Register</a>
                  <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-sm font-medium text-white border border-transparent hover:border-white hover:shadow-[0_0_10px_white] hover:bg-black hover:text-white transition duration-300">Login</a>
              @endguest
              @auth
                <span class="text-white text-sm mr-4">Welcome, {{ explode(' ', Auth::user()->name)[0] }}</span>
                <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
                  @if(Auth::user()->role === 'pengurus' || Auth::user()->role === 'pendeta')
                    <a href="/pengurus/dashboard_admin/{{ Auth::user()->name }}" class="ml-2 rounded-md px-3 py-2 text-sm font-medium text-white border border-transparent hover:border-white hover:shadow-[0_0_10px_white] hover:bg-black hover:text-white transition duration-300">Dashboard</a>
                  @else
                    <a href="{{ route('myprofile') }}" class="rounded-md px-3 py-2 text-sm font-medium text-white border border-transparent hover:border-white hover:shadow-[0_0_10px_white] hover:bg-black hover:text-white transition duration-300">My Profile</a>
                  @endif
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                  @csrf
                  <button type="submit" class="rounded-md px-3 py-2 text-sm font-medium text-white border border-transparent hover:border-white hover:shadow-[0_0_10px_white] hover:bg-black hover:text-white transition duration-300">Logout</button>
                </form>
              @endauth
          </div>
        </div>

        <div class="-mr-2 flex md:hidden">
          <!-- Mobile menu button -->
          <button type="button" @click="isOpen = !isOpen" class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-hidden" aria-controls="mobile-menu" aria-expanded="false">
            <span class="absolute -inset-0.5"></span>
            <span class="sr-only">Open main menu</span>
            <!-- Menu open: "hidden", Menu closed: "block" -->
            <svg :class="{'hidden': isOpen, 'block': !isOpen }" class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
            <!-- Menu open: "block", Menu closed: "hidden" -->
            <svg :class="{'block': isOpen, 'hidden': !isOpen }" class="hidden size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div x-show="isOpen" class="md:hidden" id="mobile-menu">
      <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
            <a href="{{ route('home') }}" class="block rounded-md px-3 py-2 text-sm font-medium text-white border border-transparent hover:border-white hover:shadow-[0_0_10px_white] hover:bg-black hover:text-white transition duration-300">Home</a>

            <!-- Dropdown Menu untuk Ibadah Raya di Mobile -->
            <x-mobile-dropdown-menu label="Ibadah Raya">
              <x-mobile-dropdown-item href="{{ route('materi-kotbah') }}">Materi Kotbah</x-mobile-dropdown-item>
              <x-mobile-dropdown-item href="{{ route('ibadah-raya') }}">Ibadah Raya</x-mobile-dropdown-item>
            </x-mobile-dropdown-menu>

            <!-- Dropdown Menu untuk Komsel di Mobile -->
            <x-mobile-dropdown-menu label="Life Group">
              <x-mobile-dropdown-item href="{{ route('materi-komsel') }}">Materi Life Group</x-mobile-dropdown-item>
              <x-mobile-dropdown-item href="{{ route('list-komsel') }}">Daftar Life Group</x-mobile-dropdown-item>
            </x-mobile-dropdown-menu>

            <a href="{{ route('youth') }}" class="rounded-md px-3 py-2 text-sm font-medium text-white border border-transparent hover:border-white hover:shadow-[0_0_10px_white] hover:bg-black hover:text-white transition duration-300">Youth</a>
            <a href="{{ route('home') }}#event" class="block rounded-md px-3 py-2 text-sm font-medium text-white border border-transparent hover:border-white hover:shadow-[0_0_10px_white] hover:bg-black hover:text-white transition duration-300">Event</a>
      </div>
      <div class="border-t border-gray-700 pt-4 pb-3">
        <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
          @guest
              <a href="{{ route('register') }}" class="block rounded-md px-3 py-2 text-sm font-medium text-white border border-transparent hover:border-white hover:shadow-[0_0_10px_white] hover:bg-black hover:text-white transition duration-300">Register</a>
              <a href="{{ route('login') }}" class="block rounded-md px-3 py-2 text-sm font-medium text-white border border-transparent hover:border-white hover:shadow-[0_0_10px_white] hover:bg-black hover:text-white transition duration-300">Login</a>
          @endguest
          @auth
            <span class="text-white text-sm mr-4">Welcome, {{ explode(' ', Auth::user()->name)[0] }}</span>
            <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
              @if(Auth::user()->role === 'pengurus' || Auth::user()->role === 'pendeta')
                <a href="/pengurus/dashboard_admin/{{ Auth::user()->name }}" class="ml-2 rounded-md px-3 py-2 text-sm font-medium text-white border border-transparent hover:border-white hover:shadow-[0_0_10px_white] hover:bg-black hover:text-white transition duration-300">Dashboard</a>
              @else
                <a href="{{ route('myprofile') }}" class="block rounded-md px-3 py-2 text-sm font-medium text-white border border-transparent hover:border-white hover:shadow-[0_0_10px_white] hover:bg-black hover:text-white transition duration-300">My Profile</a>
              @endif

            </div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
              @csrf
              <button type="submit" class="rounded-md px-3 py-2 text-sm font-medium text-white border border-transparent hover:border-white hover:shadow-[0_0_10px_white] hover:bg-black hover:text-white transition duration-300">Logout</button>
            </form>

          @endauth
        </div>
      </div>
    </div>
</nav>

