@props(['href'])

<a href="{{ $href }}" class="block rounded-md px-3 py-2 text-sm font-medium text-white border border-transparent hover:border-white hover:shadow-[0_0_10px_white] hover:bg-black hover:text-white transition duration-300">
    {{ $slot }}
</a>
