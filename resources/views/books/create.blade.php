@extends('layouts.app')

@section('title', 'Añadir libro')

@section('content')

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <a href="/books" class="text-gray-400 hover:text-amber-400 transition-colors text-sm">← Volver al catálogo</a>
        <h1 class="text-2xl font-bold text-white mt-3">Añadir nuevo libro</h1>
    </div>

    <form action="/books" method="POST" class="bg-gray-900 border border-gray-800 rounded-2xl p-6 lg:p-8 space-y-5">
        @csrf

        {{-- Título --}}
        <div>
            <label for="title" class="block text-sm font-medium text-amber-400 mb-1.5">Título *</label>
            <input type="text" name="title" id="title"
                   value="{{ old('title') }}"
                   class="w-full bg-gray-800 border {{ $errors->has('title') ? 'border-red-500' : 'border-gray-700' }} text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-amber-500 transition-colors placeholder-gray-600"
                   placeholder="Título del libro">
            @error('title')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Autor --}}
        <div>
            <label for="author" class="block text-sm font-medium text-amber-400 mb-1.5">Autor</label>
            <input type="text" name="author" id="author"
                   value="{{ old('author') }}"
                   class="w-full bg-gray-800 border {{ $errors->has('author') ? 'border-red-500' : 'border-gray-700' }} text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-amber-500 transition-colors placeholder-gray-600"
                   placeholder="Nombre del autor">
            @error('author')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Año y Puntuación --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="year" class="block text-sm font-medium text-amber-400 mb-1.5">Año</label>
                <input type="number" name="year" id="year"
                       value="{{ old('year') }}"
                       class="w-full bg-gray-800 border {{ $errors->has('year') ? 'border-red-500' : 'border-gray-700' }} text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-amber-500 transition-colors placeholder-gray-600"
                       placeholder="Ej: 2001">
                @error('year')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="rating" class="block text-sm font-medium text-amber-400 mb-1.5">Puntuación (0–10) *</label>
                <input type="number" step="0.1" name="rating" id="rating"
                       value="{{ old('rating', 5) }}"
                       min="0" max="10"
                       class="w-full bg-gray-800 border {{ $errors->has('rating') ? 'border-red-500' : 'border-gray-700' }} text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-amber-500 transition-colors">
                @error('rating')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- URL portada --}}
        <div>
            <label for="cover_url" class="block text-sm font-medium text-amber-400 mb-1.5">URL de portada</label>
            <input type="url" name="cover_url" id="cover_url"
                   value="{{ old('cover_url') }}"
                   class="w-full bg-gray-800 border {{ $errors->has('cover_url') ? 'border-red-500' : 'border-gray-700' }} text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-amber-500 transition-colors placeholder-gray-600"
                   placeholder="https://...">
            @error('cover_url')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Géneros --}}
        @if($genres->isNotEmpty())
            <div>
                <label class="block text-sm font-medium text-amber-400 mb-2">Géneros</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @foreach($genres as $genre)
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="genres[]" value="{{ $genre->id }}"
                                   {{ in_array($genre->id, old('genres', [])) ? 'checked' : '' }}
                                   class="rounded border-gray-600 bg-gray-800 text-amber-500 focus:ring-amber-500 focus:ring-offset-0">
                            <span class="text-gray-300 text-sm group-hover:text-white transition-colors">{{ $genre->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Descripción --}}
        <div>
            <label for="description" class="block text-sm font-medium text-amber-400 mb-1.5">Descripción</label>
            <textarea name="description" id="description" rows="4"
                      class="w-full bg-gray-800 border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-700' }} text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-amber-500 transition-colors placeholder-gray-600 resize-none"
                      placeholder="Sinopsis o descripción del libro...">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Actions --}}
        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-amber-500 hover:bg-amber-400 text-gray-900 font-semibold px-6 py-2.5 rounded-xl transition-colors shadow-lg shadow-amber-500/20">
                Guardar libro
            </button>
            <a href="/books"
               class="bg-gray-800 hover:bg-gray-700 text-gray-300 font-medium px-6 py-2.5 rounded-xl transition-colors border border-gray-700">
                Cancelar
            </a>
        </div>

    </form>
</div>

@endsection
