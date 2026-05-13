<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <p class="text-gray-700">
                        Bienvenido, <strong>{{ Auth::user()->name }}</strong>
                        @if(Auth::user()->isAdmin())
                            <span class="ml-2 bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full">Admin</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="/books"
                   class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition-shadow group">
                    <div class="text-3xl mb-2">📚</div>
                    <p class="font-semibold text-gray-800 group-hover:text-amber-600 transition-colors">Ver catálogo</p>
                    <p class="text-gray-500 text-sm mt-1">Explora todos los libros</p>
                </a>

                @if(Auth::user()->isAdmin())
                    <a href="/books/create"
                       class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition-shadow group">
                        <div class="text-3xl mb-2">➕</div>
                        <p class="font-semibold text-gray-800 group-hover:text-amber-600 transition-colors">Añadir libro</p>
                        <p class="text-gray-500 text-sm mt-1">Agregar al catálogo</p>
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}"
                   class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition-shadow group">
                    <div class="text-3xl mb-2">👤</div>
                    <p class="font-semibold text-gray-800 group-hover:text-amber-600 transition-colors">Mi perfil</p>
                    <p class="text-gray-500 text-sm mt-1">Editar datos de cuenta</p>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
