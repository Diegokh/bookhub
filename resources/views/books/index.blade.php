@extends('layouts.app')

@section('title', 'Catálogo')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white">Catálogo de Libros</h1>
            <p class="text-gray-400 mt-1 text-sm">{{ $books->total() }} libros disponibles</p>
        </div>

        @auth
            @if(auth()->user()->isAdmin())
                <a href="/books/create"
                   class="bg-amber-500 hover:bg-amber-400 text-gray-900 font-semibold px-5 py-2.5 rounded-xl transition-colors shadow-lg shadow-amber-500/20">
                    + Añadir libro
                </a>
            @endif
        @endauth
    </div>

    {{-- Populares --}}
    @if($popularBooks->isNotEmpty())
        <section class="mb-10">
            <div class="flex items-center gap-2 mb-4">
                <span class="text-2xl">🔥</span>
                <h2 class="text-xl font-bold text-white">Populares</h2>
                <span class="text-xs text-gray-500 ml-2">Los más leídos y reseñados</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($popularBooks as $book)
                    <a href="/books/{{ $book->id }}"
                       class="group bg-gray-900 border border-gray-800 rounded-xl overflow-hidden hover:border-amber-500/60 hover:shadow-lg hover:shadow-amber-500/10 transition-all duration-300">
                        <div class="aspect-[2/3] bg-gray-800 flex items-center justify-center overflow-hidden relative">
                            @if($book->cover_url)
                                <img src="{{ $book->cover_url }}" alt="{{ $book->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <span class="text-4xl text-gray-600">📖</span>
                            @endif
                            <span class="absolute top-2 right-2 bg-amber-500 text-gray-900 text-[10px] font-bold px-1.5 py-0.5 rounded-full">
                                {{ $book->tier }}
                            </span>
                        </div>
                        <div class="p-3">
                            <h3 class="text-white text-xs font-semibold leading-snug line-clamp-2">
                                {{ $book->title }}
                            </h3>
                            <div class="flex items-center gap-2 text-[10px] text-gray-400 mt-1">
                                <span>⭐ {{ number_format($book->rating, 1) }}</span>
                                <span>·</span>
                                <span>👁 {{ $book->readers_count }}</span>
                                <span>·</span>
                                <span>💬 {{ $book->reviews_count }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Filtros --}}
    <form method="GET" action="{{ route('books.index') }}"
          class="flex flex-wrap items-end gap-3 mb-8 bg-gray-900 border border-gray-800 rounded-2xl p-4">

        <div class="flex flex-col">
            <label for="genre" class="text-xs text-gray-400 mb-1">Género</label>
            <select name="genre" id="genre"
                    class="bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-amber-500 min-w-[180px]">
                <option value="">Todos los géneros</option>
                @foreach($genres as $g)
                    <option value="{{ $g->id }}" @selected((int) request('genre') === $g->id)>
                        {{ $g->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col">
            <label for="author" class="text-xs text-gray-400 mb-1">Autor</label>
            <select name="author" id="author"
                    class="bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-2 focus:outline-none focus:border-amber-500 min-w-[220px]">
                <option value="">Todos los autores</option>
                @foreach($authors as $a)
                    <option value="{{ $a }}" @selected(request('author') === $a)>
                        {{ $a }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit"
                class="bg-amber-500 hover:bg-amber-400 text-gray-900 font-semibold text-sm px-5 py-2 rounded-lg transition-colors">
            Filtrar
        </button>

        @if(request()->hasAny(['genre', 'author']))
            <a href="{{ route('books.index') }}"
               class="text-sm text-gray-400 hover:text-amber-400 transition-colors px-3 py-2">
                Limpiar
            </a>
        @endif
    </form>

    {{-- Grid --}}
    @if($books->isEmpty())
        <div class="text-center py-24">
            <div class="text-6xl mb-4">📭</div>
            <p class="text-gray-400 text-lg">No hay libros todavía.</p>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="/books/create" class="mt-4 inline-block text-amber-400 hover:text-amber-300 transition-colors">
                        Añadir el primero →
                    </a>
                @endif
            @endauth
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($books as $book)
                <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden hover:border-amber-500/50 hover:shadow-xl hover:shadow-amber-500/10 transition-all duration-300 group">

                    {{-- Cover --}}
                    <div class="aspect-[2/3] bg-gray-800 flex items-center justify-center overflow-hidden">
                        @if($book->cover_url)
                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="flex flex-col items-center gap-2 text-gray-600">
                                <span class="text-5xl">📖</span>
                                <span class="text-xs">Sin portada</span>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <h2 class="text-white font-semibold text-sm leading-snug line-clamp-2 flex-1">
                                {{ $book->title }}
                            </h2>
                            <span class="shrink-0 text-xs font-bold px-2 py-0.5 rounded-full {{ $book->tier_color }}">
                                {{ $book->tier }}
                            </span>
                        </div>

                        @if($book->author)
                            <p class="text-amber-400 text-xs mb-2">{{ $book->author }}</p>
                        @endif

                        <div class="flex items-center justify-between mt-3">
                            <span class="text-gray-400 text-xs">
                                ⭐ {{ number_format($book->rating, 1) }}
                                @if($book->year)
                                    · {{ $book->year }}
                                @endif
                            </span>
                            <a href="/books/{{ $book->id }}"
                               class="text-xs text-amber-400 hover:text-amber-300 font-medium transition-colors">
                                Ver más →
                            </a>
                        </div>

                        {{-- Genres --}}
                        @if($book->genres->isNotEmpty())
                            <div class="flex flex-wrap gap-1 mt-3">
                                @foreach($book->genres->take(2) as $genre)
                                    <span class="bg-gray-800 text-gray-400 text-xs px-2 py-0.5 rounded-full border border-gray-700">
                                        {{ $genre->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-10">
            {{ $books->links() }}
        </div>
    @endif

</div>

@endsection
