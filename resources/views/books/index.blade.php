@extends('layouts.app')

@section('title', 'Catálogo')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
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
