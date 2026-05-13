@extends('layouts.app')

@section('title', 'Mis libros leídos')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Mis libros leídos</h1>
        <p class="text-gray-400 mt-1 text-sm">{{ $books->total() }} libro{{ $books->total() === 1 ? '' : 's' }} en tu lista</p>
    </div>

    @if($books->isEmpty())
        <div class="text-center py-24 bg-gray-900/50 border border-dashed border-gray-800 rounded-2xl">
            <div class="text-6xl mb-4">📚</div>
            <p class="text-gray-400 text-lg mb-2">Aún no has marcado ningún libro como leído.</p>
            <a href="/books" class="text-amber-400 hover:text-amber-300 transition-colors text-sm">Explora el catálogo →</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($books as $book)
                <a href="/books/{{ $book->id }}" class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden hover:border-amber-500/50 hover:shadow-xl hover:shadow-amber-500/10 transition-all duration-300 group block">

                    <div class="aspect-[2/3] bg-gray-800 flex items-center justify-center overflow-hidden relative">
                        @if($book->cover_url)
                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="flex flex-col items-center gap-2 text-gray-600">
                                <span class="text-5xl">📖</span>
                            </div>
                        @endif
                        <span class="absolute top-2 right-2 bg-green-600/90 text-white text-xs font-semibold px-2 py-0.5 rounded-full">
                            ✓ Leído
                        </span>
                    </div>

                    <div class="p-4">
                        <h2 class="text-white font-semibold text-sm leading-snug line-clamp-2 mb-1">{{ $book->title }}</h2>
                        @if($book->author)
                            <p class="text-amber-400 text-xs mb-2">{{ $book->author }}</p>
                        @endif
                        <p class="text-gray-500 text-xs">
                            Leído {{ \Carbon\Carbon::parse($book->pivot->read_at)->diffForHumans() }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-10">{{ $books->links() }}</div>
    @endif
</div>

@endsection
