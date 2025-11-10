@props(['label', 'align' => 'left'])

<div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
    <button class="rounded-md px-3 py-2 text-sm font-medium text-white border border-transparent hover:border-white hover:shadow-[0_0_10px_white] hover:bg-black hover:text-white transition duration-300 flex items-center">
        {{ $label }}
        <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="absolute {{ $align === 'right' ? 'right-0' : 'left-0' }} mt-2 w-48 rounded-md shadow-lg bg-black border border-white ring-1 ring-black ring-opacity-5"
         style="display: none;">
        <div class="py-1">
            {{ $slot }}
        </div>
    </div>
</div>
