@props(['label'])

<div x-data="{ mobileDropdownOpen: false }">
    <button @click="mobileDropdownOpen = !mobileDropdownOpen" class="w-full text-left rounded-md px-3 py-2 text-sm font-medium text-white border border-transparent hover:border-white hover:shadow-[0_0_10px_white] hover:bg-black hover:text-white transition duration-300 flex items-center justify-between">
        <span>{{ $label }}</span>
        <svg class="h-4 w-4" :class="{ 'transform rotate-180': mobileDropdownOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <div x-show="mobileDropdownOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="pl-6 space-y-1 mt-1">
        {{ $slot }}
    </div>
</div>
