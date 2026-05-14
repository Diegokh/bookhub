<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Acceso') — BookHub</title>

    <link rel="icon" type="image/png" href="{{ asset('images/bookhub-logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-gray-100 antialiased min-h-screen flex flex-col font-sans">

    {{-- Glow de fondo --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[700px] h-[500px] bg-amber-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-amber-700/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative flex-1 flex flex-col items-center px-4 pt-8 pb-10">

        {{-- Wrapper logo + card --}}
        <div class="w-full max-w-md flex flex-col items-center">

            {{-- Logo — el -mb-10 cancela el whitespace inferior del PNG,
                 dejando una separación visual moderada con el card --}}
            <a href="/" class="-mb-10" aria-label="BookHub - inicio">
                <img src="{{ asset('images/bookhub-logo.png') }}"
                     alt="BookHub"
                     class="h-48 sm:h-56 w-auto hover:opacity-90 transition-opacity">
            </a>

            {{-- Card del formulario --}}
            <div class="w-full bg-gray-900/80 backdrop-blur-sm border border-gray-800 rounded-3xl shadow-2xl p-8">
                {{ $slot }}
            </div>
        </div>

        {{-- Link volver al sitio --}}
        <a href="/" class="mt-6 text-gray-500 hover:text-amber-400 text-sm transition-colors">
            ← Volver al sitio
        </a>
    </div>

</body>
</html>
