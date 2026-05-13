@extends('layouts.app')

@section('title', $book->title)

@section('content')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Back link --}}
    <a href="/books" class="inline-flex items-center gap-2 text-gray-400 hover:text-amber-400 transition-colors text-sm mb-8">
        ← Volver al catálogo
    </a>

    {{-- ============================ Book card ============================ --}}
    <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden shadow-2xl">
        <div class="flex flex-col md:flex-row gap-0">

            {{-- Cover --}}
            <div class="md:w-64 lg:w-72 shrink-0 bg-gray-800 flex items-center justify-center min-h-64">
                @if($book->cover_url)
                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
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
                <div class="flex flex-wrap gap-4 mb-4">
                    @if($book->year)
                        <div class="flex items-center gap-1.5 text-gray-300 text-sm">
                            <span class="text-gray-500">📅</span> {{ $book->year }}
                        </div>
                    @endif
                    <div class="flex items-center gap-1.5 text-gray-300 text-sm">
                        <span>⭐</span>
                        <span class="font-semibold">{{ number_format($book->rating, 1) }}</span>
                        <span class="text-gray-500 text-xs">editorial</span>
                    </div>
                    @if($book->community_rating !== null)
                        <div class="flex items-center gap-1.5 text-gray-300 text-sm">
                            <span>👥</span>
                            <span class="font-semibold">{{ number_format($book->community_rating, 1) }}</span>
                            <span class="text-gray-500 text-xs">comunidad ({{ $book->reviews->count() }})</span>
                        </div>
                    @endif
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

                {{-- Action buttons row --}}
                <div class="flex flex-wrap gap-3 mt-4">
                    @auth
                        {{-- Toggle "leído" --}}
                        <form action="/books/{{ $book->id }}/read" method="POST">
                            @csrf
                            @if($hasRead)
                                <button type="submit"
                                        class="bg-green-600/20 border border-green-500 text-green-400 hover:bg-green-600/30 font-semibold text-sm px-4 py-2 rounded-xl transition-colors flex items-center gap-2">
                                    ✓ Leído — Quitar
                                </button>
                            @else
                                <button type="submit"
                                        class="bg-gray-800 hover:bg-gray-700 border border-gray-700 text-gray-200 font-semibold text-sm px-4 py-2 rounded-xl transition-colors">
                                    + Marcar como leído
                                </button>
                            @endif
                        </form>

                        {{-- Admin actions --}}
                        @if(auth()->user()->isAdmin())
                            <a href="/books/{{ $book->id }}/edit"
                               class="bg-amber-500 hover:bg-amber-400 text-gray-900 font-semibold text-sm px-4 py-2 rounded-xl transition-colors">
                                Editar
                            </a>
                            <form action="/books/{{ $book->id }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar este libro definitivamente?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 hover:bg-red-500 text-white font-semibold text-sm px-4 py-2 rounded-xl transition-colors">
                                    Eliminar
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="text-sm text-amber-400 hover:text-amber-300">Inicia sesión para marcar como leído y reseñar</a>
                    @endauth
                </div>

            </div>
        </div>
    </div>

    {{-- ============================ Tu reseña ============================ --}}
    @auth
        <div class="mt-10 bg-gray-900 border border-gray-800 rounded-2xl p-6 lg:p-8">
            <h2 class="text-xl font-bold text-white mb-4">
                {{ $userReview ? 'Tu reseña' : 'Escribe tu reseña' }}
            </h2>

            <form action="{{ $userReview ? route('reviews.update', $userReview) : route('reviews.store', $book->id) }}"
                  method="POST" class="space-y-4">
                @csrf
                @if($userReview) @method('PUT') @endif

                <div class="flex items-center gap-4">
                    <label for="rating" class="text-sm text-amber-400 font-medium">Valoración</label>
                    <input type="number" name="rating" id="rating" min="1" max="10" step="1" required
                           value="{{ old('rating', $userReview->rating ?? 8) }}"
                           class="bg-gray-800 border {{ $errors->has('rating') ? 'border-red-500' : 'border-gray-700' }} text-white rounded-xl px-3 py-2 text-sm w-24 focus:outline-none focus:border-amber-500">
                    <span class="text-gray-500 text-sm">/ 10</span>
                </div>
                @error('rating') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror

                <div>
                    <label for="body" class="block text-sm text-amber-400 font-medium mb-1.5">Texto (opcional)</label>
                    <textarea name="body" id="body" rows="3"
                              placeholder="¿Qué te ha parecido?"
                              class="w-full bg-gray-800 border {{ $errors->has('body') ? 'border-red-500' : 'border-gray-700' }} text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-amber-500 placeholder-gray-600 resize-none">{{ old('body', $userReview->body ?? '') }}</textarea>
                    @error('body') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            class="bg-amber-500 hover:bg-amber-400 text-gray-900 font-semibold text-sm px-5 py-2 rounded-xl transition-colors">
                        {{ $userReview ? 'Actualizar reseña' : 'Publicar reseña' }}
                    </button>
                    @if($userReview)
                        <button type="button" onclick="document.getElementById('delete-my-review').submit()"
                                class="bg-gray-800 hover:bg-red-600 text-gray-300 hover:text-white font-semibold text-sm px-5 py-2 rounded-xl transition-colors border border-gray-700">
                            Borrar mi reseña
                        </button>
                    @endif
                </div>
            </form>

            @if($userReview)
                <form id="delete-my-review" action="{{ route('reviews.destroy', $userReview) }}" method="POST" class="hidden"
                      onsubmit="return confirm('¿Borrar tu reseña? Se eliminarán también los comentarios.')">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </div>
    @endauth

    {{-- ============================ Reseñas comunidad ============================ --}}
    <div class="mt-10">
        <h2 class="text-xl font-bold text-white mb-4">
            Reseñas de la comunidad
            <span class="text-gray-500 text-base font-normal">({{ $book->reviews->count() }})</span>
        </h2>

        @if($book->reviews->isEmpty())
            <div class="text-center py-12 bg-gray-900/50 border border-dashed border-gray-800 rounded-2xl">
                <div class="text-4xl mb-2">💬</div>
                <p class="text-gray-400 text-sm">Aún no hay reseñas. Sé el primero.</p>
            </div>
        @else
            <div class="space-y-5">
                @foreach($book->reviews as $review)
                    @include('books.partials.review', ['review' => $review])
                @endforeach
            </div>
        @endif
    </div>

</div>

@endsection
