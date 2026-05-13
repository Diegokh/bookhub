@extends('layouts.app')

@section('title', 'Editar — ' . $book->title)

@section('content')

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <a href="/books/{{ $book->id }}" class="text-gray-400 hover:text-amber-400 transition-colors text-sm">← Volver al libro</a>
        <h1 class="text-2xl font-bold text-white mt-3">Editar: <span class="text-amber-400">{{ $book->title }}</span></h1>
    </div>

    {{-- ============================ Buscador Open Library (opcional) ============================ --}}
    <div x-data="openLibrarySearch()" class="bg-gradient-to-br from-amber-500/10 to-amber-700/5 border border-amber-500/30 rounded-2xl p-5 mb-6">
        <details>
            <summary class="cursor-pointer flex items-center gap-2 text-sm font-semibold text-amber-400 hover:text-amber-300">
                <span>🔍</span> Re-rellenar campos desde Open Library
            </summary>

            <p class="text-gray-400 text-xs mt-3 mb-3">Sobrescribirá los campos del formulario con los datos del libro que selecciones.</p>

            <div class="relative">
                <input type="text" x-model="query" @input.debounce.400ms="search()"
                       placeholder="Buscar otro libro..."
                       class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-amber-500 placeholder-gray-600">
                <div x-show="loading" x-cloak class="absolute right-3 top-1/2 -translate-y-1/2 text-amber-400 text-sm">⏳</div>
            </div>

            <div x-show="results.length > 0" x-cloak class="mt-3 bg-gray-900 border border-gray-800 rounded-xl divide-y divide-gray-800 max-h-80 overflow-y-auto">
                <template x-for="r in results" :key="r.key">
                    <button type="button" @click="pick(r)" :disabled="picking"
                            class="w-full flex items-start gap-3 p-3 hover:bg-gray-800 transition-colors text-left disabled:opacity-50">
                        <div class="w-12 h-16 shrink-0 bg-gray-800 rounded overflow-hidden flex items-center justify-center">
                            <template x-if="r.cover_url"><img :src="r.cover_url" class="w-full h-full object-cover"></template>
                            <template x-if="!r.cover_url"><span class="text-2xl text-gray-600">📖</span></template>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white text-sm font-medium truncate" x-text="r.title"></p>
                            <p class="text-amber-400 text-xs truncate" x-text="r.author || 'Autor desconocido'"></p>
                            <p class="text-gray-500 text-xs" x-text="r.year || ''"></p>
                        </div>
                    </button>
                </template>
            </div>

            <p x-show="searched && results.length === 0 && !loading" x-cloak class="mt-3 text-gray-500 text-xs">Sin resultados.</p>
            <p x-show="error" x-cloak class="mt-3 text-red-400 text-xs" x-text="error"></p>
            <p x-show="picking" x-cloak class="mt-3 text-amber-400 text-xs">Cargando detalle…</p>
        </details>
    </div>

    <form action="/books/{{ $book->id }}" method="POST" class="bg-gray-900 border border-gray-800 rounded-2xl p-6 lg:p-8 space-y-5">
        @csrf
        @method('PUT')

        {{-- Título --}}
        <div>
            <label for="title" class="block text-sm font-medium text-amber-400 mb-1.5">Título *</label>
            <input type="text" name="title" id="title"
                   value="{{ old('title', $book->title) }}"
                   class="w-full bg-gray-800 border {{ $errors->has('title') ? 'border-red-500' : 'border-gray-700' }} text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-amber-500 transition-colors">
            @error('title')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Autor --}}
        <div>
            <label for="author" class="block text-sm font-medium text-amber-400 mb-1.5">Autor</label>
            <input type="text" name="author" id="author"
                   value="{{ old('author', $book->author) }}"
                   class="w-full bg-gray-800 border {{ $errors->has('author') ? 'border-red-500' : 'border-gray-700' }} text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-amber-500 transition-colors">
            @error('author')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Año y Puntuación --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="year" class="block text-sm font-medium text-amber-400 mb-1.5">Año</label>
                <input type="number" name="year" id="year"
                       value="{{ old('year', $book->year) }}"
                       class="w-full bg-gray-800 border {{ $errors->has('year') ? 'border-red-500' : 'border-gray-700' }} text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-amber-500 transition-colors">
                @error('year')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="rating" class="block text-sm font-medium text-amber-400 mb-1.5">Puntuación (0–10) *</label>
                <input type="number" step="0.1" name="rating" id="rating"
                       value="{{ old('rating', $book->rating) }}"
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
                   value="{{ old('cover_url', $book->cover_url) }}"
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
                                   {{ in_array($genre->id, old('genres', $book->genres->pluck('id')->toArray())) ? 'checked' : '' }}
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
                      class="w-full bg-gray-800 border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-700' }} text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-amber-500 transition-colors resize-none">{{ old('description', $book->description) }}</textarea>
            @error('description')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Actions --}}
        <div class="flex gap-3 pt-2">
            <button type="submit"
                    class="bg-amber-500 hover:bg-amber-400 text-gray-900 font-semibold px-6 py-2.5 rounded-xl transition-colors shadow-lg shadow-amber-500/20">
                Actualizar libro
            </button>
            <a href="/books/{{ $book->id }}"
               class="bg-gray-800 hover:bg-gray-700 text-gray-300 font-medium px-6 py-2.5 rounded-xl transition-colors border border-gray-700">
                Cancelar
            </a>
        </div>

    </form>
</div>

<script>
    function openLibrarySearch() {
        return {
            query: '', results: [], loading: false, picking: false, searched: false, error: null,
            async search() {
                this.error = null;
                if (this.query.trim().length < 2) { this.results = []; this.searched = false; return; }
                this.loading = true;
                try {
                    const url = '{{ route('admin.openlibrary.search') }}?q=' + encodeURIComponent(this.query);
                    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) throw new Error('Búsqueda falló (' + res.status + ')');
                    const data = await res.json();
                    this.results = data.results || [];
                    this.searched = true;
                } catch (e) { this.error = e.message; this.results = []; }
                finally { this.loading = false; }
            },
            async pick(r) {
                this.picking = true; this.error = null;
                try {
                    this.setField('title', r.title || '');
                    this.setField('author', r.author || '');
                    this.setField('year', r.year || '');
                    this.setField('cover_url', r.cover_url || '');
                    const url = '{{ route('admin.openlibrary.work') }}?key=' + encodeURIComponent(r.key);
                    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    if (res.ok) {
                        const data = await res.json();
                        if (data.description) this.setField('description', data.description);
                        if (!r.cover_url && data.cover_url) this.setField('cover_url', data.cover_url);
                    }
                    this.results = []; this.query = r.title || '';
                } catch (e) { this.error = 'No se pudo cargar el detalle: ' + e.message; }
                finally { this.picking = false; }
            },
            setField(name, value) {
                const el = document.getElementById(name);
                if (el) { el.value = value; el.dispatchEvent(new Event('input', { bubbles: true })); }
            },
        };
    }
</script>

@endsection
