<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BookHub') — BookHub</title>
    <link rel="icon" type="image/png" href="{{ asset('images/bookhub-logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-950 text-gray-100 min-h-screen flex flex-col font-sans antialiased">

    {{-- Navbar --}}
    <nav class="bg-gray-900 border-b border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Brand --}}
                <a href="/" class="flex items-center group -my-8 relative z-10" aria-label="BookHub - inicio">
                    <img src="{{ asset('images/bookhub-logo.png') }}"
                         alt="BookHub"
                         class="h-32 w-auto group-hover:opacity-90 transition-opacity">
                </a>

                {{-- Nav links --}}
                <div class="hidden sm:flex items-center gap-6">
                    <a href="/books"
                       class="text-gray-300 hover:text-amber-400 transition-colors text-sm font-medium {{ request()->is('books*') && !request()->is('my-books*') ? 'text-amber-400' : '' }}">
                        Catálogo
                    </a>

                    @auth
                        <a href="{{ route('my-books.index') }}"
                           class="text-gray-300 hover:text-amber-400 transition-colors text-sm font-medium {{ request()->is('my-books*') ? 'text-amber-400' : '' }}">
                            Mis leídos
                        </a>
                        @if(auth()->user()->isAdmin())
                            <a href="/books/create"
                               class="bg-amber-500 hover:bg-amber-400 text-gray-900 font-semibold text-sm px-4 py-1.5 rounded-full transition-colors">
                                + Añadir libro
                            </a>
                        @endif
                        <div class="flex items-center gap-4">
                            <a href="{{ route('dashboard') }}"
                               class="text-gray-300 hover:text-amber-400 transition-colors text-sm">
                                {{ Auth::user()->name }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="text-gray-500 hover:text-red-400 transition-colors text-sm">
                                    Salir
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-gray-300 hover:text-amber-400 transition-colors text-sm font-medium">
                            Iniciar sesión
                        </a>
                        <a href="{{ route('register') }}"
                           class="bg-amber-500 hover:bg-amber-400 text-gray-900 font-semibold text-sm px-4 py-1.5 rounded-full transition-colors">
                            Registro
                        </a>
                    @endauth
                </div>

                {{-- Mobile menu button --}}
                <button id="mobile-menu-btn" class="sm:hidden text-gray-400 hover:text-white p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            {{-- Mobile menu --}}
            <div id="mobile-menu" class="hidden sm:hidden pb-4 space-y-2">
                <a href="/books" class="block text-gray-300 hover:text-amber-400 py-2 text-sm">Catálogo</a>
                @auth
                    <a href="{{ route('my-books.index') }}" class="block text-gray-300 hover:text-amber-400 py-2 text-sm">Mis leídos</a>
                    @if(auth()->user()->isAdmin())
                        <a href="/books/create" class="block text-amber-400 py-2 text-sm">+ Añadir libro</a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="block text-gray-300 hover:text-amber-400 py-2 text-sm">{{ Auth::user()->name }}</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-red-400 text-sm py-2">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block text-gray-300 hover:text-amber-400 py-2 text-sm">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="block text-amber-400 py-2 text-sm">Registro</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="bg-green-900/50 border-b border-green-700 text-green-300 text-sm text-center py-3 px-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-900/50 border-b border-red-700 text-red-300 text-sm text-center py-3 px-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Main content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 border-t border-gray-800 py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-500 text-sm">
                <span class="text-amber-400 font-semibold">BookHub</span> © {{ date('Y') }} — Tu biblioteca digital
            </p>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
            document.getElementById('mobile-menu')?.classList.toggle('hidden');
        });
    </script>

</body>
</html>
