<x-guest-layout>

    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-white">Bienvenido de vuelta</h1>
        <p class="text-gray-400 text-sm mt-1">Inicia sesión para continuar leyendo</p>
    </div>

    {{-- Session Status (ej. "password reset") --}}
    @if (session('status'))
        <div class="mb-4 bg-green-500/10 border border-green-500/30 text-green-400 text-sm rounded-xl p-3">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-amber-400 text-sm font-medium mb-1.5">
                Email
            </label>
            <input id="email" name="email" type="email" required autofocus autocomplete="username"
                   value="{{ old('email') }}"
                   class="w-full bg-gray-800 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-700' }} text-white rounded-xl px-4 py-2.5 text-sm placeholder-gray-600 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500/40"
                   placeholder="tu@email.com">
            @error('email') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-amber-400 text-sm font-medium">
                    Contraseña
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-gray-400 hover:text-amber-400 transition-colors">
                        ¿La olvidaste?
                    </a>
                @endif
            </div>
            <input id="password" name="password" type="password" required autocomplete="current-password"
                   class="w-full bg-gray-800 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-700' }} text-white rounded-xl px-4 py-2.5 text-sm placeholder-gray-600 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500/40"
                   placeholder="••••••••">
            @error('password') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
        </div>

        {{-- Remember me --}}
        <label for="remember_me" class="flex items-center gap-2 cursor-pointer select-none">
            <input id="remember_me" name="remember" type="checkbox"
                   class="rounded border-gray-700 bg-gray-800 text-amber-500 focus:ring-amber-500/40 focus:ring-offset-gray-900">
            <span class="text-sm text-gray-400">Mantener sesión iniciada</span>
        </label>

        {{-- Submit --}}
        <button type="submit"
                class="w-full bg-amber-500 hover:bg-amber-400 text-gray-900 font-bold text-sm py-3 rounded-xl transition-colors shadow-lg shadow-amber-500/20 hover:shadow-amber-500/40">
            Iniciar sesión
        </button>
    </form>

    {{-- Footer link a registro --}}
    <div class="mt-6 pt-6 border-t border-gray-800 text-center">
        <p class="text-gray-400 text-sm">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}" class="text-amber-400 hover:text-amber-300 font-medium ml-1 transition-colors">
                Regístrate gratis
            </a>
        </p>
    </div>

</x-guest-layout>
