@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

@php
    $user        = auth()->user();
    $booksRead   = $user->readBooks()->count();
    $reviewsCount = $user->reviews()->count();
    $commentsCount = $user->comments()->count();
    $isAdmin     = $user->isAdmin();
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    {{-- ====================== Hero / Bienvenida ====================== --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-900 to-amber-900/20 border border-gray-800 rounded-3xl p-6 sm:p-10">
        <div class="absolute top-0 right-0 w-72 h-72 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative">
            <p class="text-amber-400 text-sm font-medium mb-1">Bienvenido de nuevo</p>
            <h1 class="text-3xl sm:text-4xl font-bold text-white flex items-center gap-3 flex-wrap">
                {{ $user->name }}
                @if($isAdmin)
                    <span class="text-xs bg-red-500/15 text-red-400 border border-red-500/20 px-2.5 py-1 rounded-full font-semibold">Admin</span>
                @endif
            </h1>
            <p class="text-gray-400 mt-2 text-sm">{{ $user->email }}</p>
        </div>
    </section>

    {{-- ====================== Stats ====================== --}}
    <section class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 hover:border-amber-500/40 transition-colors">
            <div class="flex items-center justify-between mb-2">
                <span class="text-2xl">📖</span>
                <span class="text-3xl font-bold text-amber-400">{{ $booksRead }}</span>
            </div>
            <p class="text-gray-400 text-sm">{{ $booksRead === 1 ? 'libro leído' : 'libros leídos' }}</p>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 hover:border-amber-500/40 transition-colors">
            <div class="flex items-center justify-between mb-2">
                <span class="text-2xl">⭐</span>
                <span class="text-3xl font-bold text-amber-400">{{ $reviewsCount }}</span>
            </div>
            <p class="text-gray-400 text-sm">{{ $reviewsCount === 1 ? 'reseña escrita' : 'reseñas escritas' }}</p>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 hover:border-amber-500/40 transition-colors">
            <div class="flex items-center justify-between mb-2">
                <span class="text-2xl">💬</span>
                <span class="text-3xl font-bold text-amber-400">{{ $commentsCount }}</span>
            </div>
            <p class="text-gray-400 text-sm">{{ $commentsCount === 1 ? 'comentario' : 'comentarios' }}</p>
        </div>
    </section>

    {{-- ====================== Acciones rápidas ====================== --}}
    <section>
        <h2 class="text-lg font-bold text-white mb-4">Accesos rápidos</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ $isAdmin ? 4 : 3 }} gap-4">

            <a href="/books"
               class="group bg-gray-900 border border-gray-800 rounded-2xl p-6 hover:border-amber-500/60 hover:shadow-lg hover:shadow-amber-500/10 transition-all">
                <div class="text-3xl mb-3">📚</div>
                <p class="text-white font-semibold group-hover:text-amber-400 transition-colors">Ver catálogo</p>
                <p class="text-gray-500 text-sm mt-1">Explora todos los libros</p>
            </a>

            <a href="{{ route('my-books.index') }}"
               class="group bg-gray-900 border border-gray-800 rounded-2xl p-6 hover:border-amber-500/60 hover:shadow-lg hover:shadow-amber-500/10 transition-all">
                <div class="text-3xl mb-3">✅</div>
                <p class="text-white font-semibold group-hover:text-amber-400 transition-colors">Mis leídos</p>
                <p class="text-gray-500 text-sm mt-1">Tu biblioteca personal</p>
            </a>

            @if($isAdmin)
                <a href="/books/create"
                   class="group bg-amber-500/10 border border-amber-500/30 rounded-2xl p-6 hover:bg-amber-500/15 hover:border-amber-500/60 transition-all">
                    <div class="text-3xl mb-3">➕</div>
                    <p class="text-amber-400 font-semibold">Añadir libro</p>
                    <p class="text-amber-400/70 text-sm mt-1">Agregar al catálogo</p>
                </a>
            @endif

            <a href="{{ route('profile.edit') }}"
               class="group bg-gray-900 border border-gray-800 rounded-2xl p-6 hover:border-amber-500/60 hover:shadow-lg hover:shadow-amber-500/10 transition-all">
                <div class="text-3xl mb-3">👤</div>
                <p class="text-white font-semibold group-hover:text-amber-400 transition-colors">Mi perfil</p>
                <p class="text-gray-500 text-sm mt-1">Editar datos de cuenta</p>
            </a>
        </div>
    </section>

    {{-- ====================== Última actividad ====================== --}}
    @php
        $recentReviews = $user->reviews()->with('book')->latest()->limit(3)->get();
    @endphp

    @if($recentReviews->isNotEmpty())
        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-white">Tus últimas reseñas</h2>
                <a href="{{ route('my-books.index') }}" class="text-amber-400 hover:text-amber-300 text-sm transition-colors">
                    Ver todas →
                </a>
            </div>
            <div class="space-y-3">
                @foreach($recentReviews as $review)
                    <a href="/books/{{ $review->book_id }}"
                       class="block bg-gray-900 border border-gray-800 rounded-xl p-4 hover:border-amber-500/40 transition-colors">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <p class="text-white font-semibold text-sm truncate">{{ $review->book->title ?? 'Libro eliminado' }}</p>
                                @if($review->body)
                                    <p class="text-gray-400 text-sm mt-1 line-clamp-2">{{ $review->body }}</p>
                                @endif
                            </div>
                            <div class="shrink-0 text-right">
                                <span class="text-amber-400 font-bold text-sm">⭐ {{ $review->rating }}/10</span>
                                <p class="text-gray-500 text-xs mt-1">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

</div>

@endsection
