<!DOCTYPE html>
<html lang="en" class="h-full bg-black">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- @vite('resources/css/app.css') --}}
    @php
        $isProduction = app()->environment('production');
        $manifestPath = $isProduction ? '../public_html/build/manifest.json' : public_path('build/manifest.json');
    @endphp

    @if ($isProduction && file_exists($manifestPath))
            @php
                $manifest = json_decode(file_get_contents($manifestPath), true);
            @endphp
           {{-- /build/{{$manifest['resources/css/app.css']['file']}}">
            <script type="module" src="{{config('app.url')}}/build/{{$manifest['resources/js/app.js']['file']}}"></script> --}}

            <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}">
            <script type="module" src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}"> </script>
    @else
            @viteReactRefresh
            @vite(['resources/js/app.js','resources/css/app.css'])
    @endif
    <title>GBI PLC</title>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://unpkg.com/feather-icons"></script>
    <!-- Removed Bootstrap JS bundle to avoid conflict with Tailwind -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>

</head>
<body class="h-full">
    <div class="min-h-full">
        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>

</html>