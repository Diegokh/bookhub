<x-guest-layout>

    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-white">Crear cuenta</h1>
        <p class="text-gray-400 text-sm mt-1">Únete a la comunidad de BookHub</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block text-amber-400 text-sm font-medium mb-1.5">
                Nombre
            </label>
            <input id="name" name="name" type="text" required autofocus autocomplete="name"
                   value="{{ old('name') }}"
                   class="w-full bg-gray-800 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-700' }} text-white rounded-xl px-4 py-2.5 text-sm placeholder-gray-600 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500/40"
                   placeholder="Tu nombre">
            @error('name') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-amber-400 text-sm font-medium mb-1.5">
                Email
            </label>
            <input id="email" name="email" type="email" required autocomplete="username"
                   value="{{ old('email') }}"
                   class="w-full bg-gray-800 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-700' }} text-white rounded-xl px-4 py-2.5 text-sm placeholder-gray-600 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500/40"
                   placeholder="tu@email.com">
            @error('email') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-amber-400 text-sm font-medium mb-1.5">
                Contraseña
            </label>
            <input id="password" name="password" type="password" required autocomplete="new-password"
                   class="w-full bg-gray-800 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-700' }} text-white rounded-xl px-4 py-2.5 text-sm placeholder-gray-600 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500/40"
                   placeholder="Mínimo 8 caracteres">
            @error('password') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-amber-400 text-sm font-medium mb-1.5">
                Confirmar contraseña
            </label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                   class="w-full bg-gray-800 border {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-gray-700' }} text-white rounded-xl px-4 py-2.5 text-sm placeholder-gray-600 focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500/40"
                   placeholder="Repite la contraseña">
            @error('password_confirmation') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full bg-amber-500 hover:bg-amber-400 text-gray-900 font-bold text-sm py-3 rounded-xl transition-colors shadow-lg shadow-amber-500/20 hover:shadow-amber-500/40">
            Crear cuenta
        </button>
    </form>

    {{-- Footer link a login --}}
    <div class="mt-6 pt-6 border-t border-gray-800 text-center">
        <p class="text-gray-400 text-sm">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="text-amber-400 hover:text-amber-300 font-medium ml-1 transition-colors">
                Iniciar sesión
            </a>
        </p>
    </div>

</x-guest-layout>
