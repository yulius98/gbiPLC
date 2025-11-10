@props(['href'])

<a href="{{ $href }}" class="block rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 transition duration-300">
    {{ $slot }}
</a>
