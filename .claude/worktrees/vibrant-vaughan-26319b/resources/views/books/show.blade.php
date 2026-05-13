@extends('layouts.app')

@section('title', $book->title)

@section('content')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Back link --}}
    <a href="/books" class="inline-flex items-center gap-2 text-gray-400 hover:text-amber-400 transition-colors text-sm mb-8">
        ← Volver al catálogo
    </a>

    {{-- Book card --}}
    <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden shadow-2xl">
        <div class="flex flex-col md:flex-row gap-0">

            {{-- Cover --}}
            <div class="md:w-64 lg:w-72 shrink-0 bg-gray-800 flex items-center justify-center min-h-64">
                @if($book->cover_url)
                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="flex flex-col items-center gap-3 text-gray-600 p-8">
                        <span class="text-7xl">📖</span>
                        <span class="text-sm">Sin portada</span>
                    </div>
                @endif
            </div>

            {{-- Details --}}
            <div class="flex-1 p-6 lg:p-8">

                <div class="flex items-start justify-between gap-4 mb-2">
                    <h1 class="text-2xl lg:text-3xl font-bold text-white leading-tight">{{ $book->title }}</h1>
                    <span class="shrink-0 text-lg font-bold px-3 py-1 rounded-xl {{ $book->tier_color }}">
                        Tier {{ $book->tier }}
                    </span>
                </div>

                @if($book->author)
                    <p class="text-amber-400 text-lg font-medium mb-4">{{ $book->author }}</p>
                @endif

                {{-- Meta --}}
                <div class="flex flex-wrap gap-4 mb-6">
                    @if($book->year)
                        <div class="flex items-center gap-1.5 text-gray-300 text-sm">
                            <span class="text-gray-500">📅</span>
                            {{ $book->year }}
                        </div>
                    @endif
                    <div class="flex items-center gap-1.5 text-gray-300 text-sm">
                        <span>⭐</span>
                        <span class="font-semibold">{{ number_format($book->rating, 1) }}</span>
                        <span class="text-gray-500">/ 10</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-gray-300 text-sm">
                        <span class="text-gray-500">💬</span>
                        {{ $book->reviews->count() }} reseña{{ $book->reviews->count() !== 1 ? 's' : '' }}
                    </div>
                </div>

                {{-- Genres --}}
                @if($book->genres->isNotEmpty())
                    <div class="flex flex-wrap gap-2 mb-6">
                        @foreach($book->genres as $genre)
                            <span class="bg-gray-800 border border-gray-700 text-gray-300 text-xs px-3 py-1 rounded-full">
                                {{ $genre->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                {{-- Description --}}
                @if($book->description)
                    <div class="bg-gray-800/50 rounded-xl p-4 mb-6">
                        <p class="text-gray-300 text-sm leading-relaxed">{{ $book->description }}</p>
                    </div>
                @endif

                {{-- Admin actions --}}
                @auth
                    @if(auth()->user()->isAdmin())
                        <div class="flex gap-3 mt-4">
                            <a href="/books/{{ $book->id }}/edit"
                               class="bg-amber-500 hover:bg-amber-400 text-gray-900 font-semibold text-sm px-5 py-2 rounded-xl transition-colors">
                                Editar
                            </a>
                            <form action="/books/{{ $book->id }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar este libro definitivamente?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 hover:bg-red-500 text-white font-semibold text-sm px-5 py-2 rounded-xl transition-colors">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth

            </div>
        </div>
    </div>

    {{-- Reviews --}}
    @if($book->reviews->isNotEmpty())
        <div class="mt-10">
            <h2 class="text-xl font-bold text-white mb-4">Reseñas</h2>
            <div class="space-y-4">
                @foreach($book->reviews as $review)
                    <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-white text-sm">{{ $review->author }}</span>
                            <span class="text-amber-400 text-sm font-bold">⭐ {{ $review->rating }}/10</span>
                        </div>
                        <p class="text-gray-300 text-sm leading-relaxed">{{ $review->body }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

@endsection
