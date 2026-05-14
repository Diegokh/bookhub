<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookHub — Tu biblioteca digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen flex flex-col font-sans antialiased">

    {{-- Navbar --}}
    <nav class="bg-gray-900/80 backdrop-blur-sm border-b border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center -my-8 relative z-10" aria-label="BookHub - inicio">
                    <img src="{{ asset('images/bookhub-logo.png') }}"
                         alt="BookHub"
                         class="h-32 w-auto hover:opacity-90 transition-opacity">
                </a>
                <div class="flex items-center gap-4">
                    <a href="/books" class="text-gray-300 hover:text-white transition-colors text-sm">Catálogo</a>
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="bg-amber-500 hover:bg-amber-400 text-gray-900 font-semibold text-sm px-4 py-1.5 rounded-full transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors text-sm">Iniciar sesión</a>
                        <a href="{{ route('register') }}"
                           class="bg-amber-500 hover:bg-amber-400 text-gray-900 font-semibold text-sm px-4 py-1.5 rounded-full transition-colors">
                            Registro
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="relative flex-1 flex items-center justify-center overflow-hidden py-24 px-4">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[600px] h-[400px] bg-amber-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 text-center max-w-3xl mx-auto">

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-4 leading-tight">
                Tu biblioteca
                <span class="text-amber-400">digital</span>
            </h1>
            <p class="text-gray-400 text-lg mb-10 max-w-xl mx-auto leading-relaxed">
                Descubre, valora y organiza los mejores libros. Desde clásicos inmortales hasta nuevas publicaciones.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/books"
                   class="bg-amber-500 hover:bg-amber-400 text-gray-900 font-bold px-8 py-3.5 rounded-2xl transition-all shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 text-base">
                    Explorar catálogo
                </a>
                @guest
                    <a href="{{ route('register') }}"
                       class="bg-gray-800 hover:bg-gray-700 text-white font-semibold px-8 py-3.5 rounded-2xl transition-colors border border-gray-700 text-base">
                        Crear cuenta
                    </a>
                @endguest
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="py-16 px-4 border-t border-gray-900">
        <div class="max-w-5xl mx-auto grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
            <div class="p-6">
                <div class="text-4xl mb-3">🔍</div>
                <h3 class="text-white font-semibold mb-2">Catálogo completo</h3>
                <p class="text-gray-500 text-sm">Busca y filtra entre cientos de títulos organizados por género y puntuación.</p>
            </div>
            <div class="p-6">
                <div class="text-4xl mb-3">⭐</div>
                <h3 class="text-white font-semibold mb-2">Sistema de puntuación</h3>
                <p class="text-gray-500 text-sm">Cada libro lleva una puntuación y tier (S/A/B/C) para elegir con criterio.</p>
            </div>
            <div class="p-6">
                <div class="text-4xl mb-3">💬</div>
                <h3 class="text-white font-semibold mb-2">Reseñas</h3>
                <p class="text-gray-500 text-sm">Lee y comparte opiniones sobre los libros del catálogo.</p>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 border-t border-gray-800 py-6 text-center">
        <p class="text-gray-500 text-sm">
            <span class="text-amber-400 font-semibold">BookHub</span> © {{ date('Y') }} — Tu biblioteca digital
        </p>
    </footer>

</body>
</html>
