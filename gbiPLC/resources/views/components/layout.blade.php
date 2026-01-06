<!DOCTYPE html>
<html lang="en" class="h-full">
<!-- <html lang="id" class="h-full"> -->
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

    <!-- Force favicon refresh -->
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="pragma" content="no-cache">

    <!-- Favicon with cache buster -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('logoplc.png') }}?v={{ time() }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('logoplc.png') }}?v={{ time() }}">

    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://unpkg.com/feather-icons"></script>
    <!-- Removed Bootstrap JS bundle to avoid conflict with Tailwind -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>

</head>
<body class="h-full" >
    <div class="min-h-full">
        <main>
            <div class="mx-auto max-w-7xl px-1 py-6 sm:px-2 lg:px-2">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>



</html>
<style>
    body {
        background-image: url('{{ asset('BGLayout.jpg') }}');
        background-repeat: repeat;
        background-size: contain;
        background-position: center 40px;
        background-attachment: fixed;
    }
    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.3);
        z-index: -1;
    }

</style>

