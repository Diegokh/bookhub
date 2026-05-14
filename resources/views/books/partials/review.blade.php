@php
    $isMyReview      = auth()->check() && auth()->id() === $review->user_id;
    $canDeleteReview = auth()->check() && (auth()->user()->can('delete', $review));
@endphp

<article class="bg-gray-900 border border-gray-800 rounded-2xl p-5">

    {{-- Cabecera --}}
    <header class="flex items-start justify-between gap-4 mb-3">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-amber-400 font-semibold text-sm">
                {{ strtoupper(substr($review->user->name ?? '?', 0, 1)) }}
            </div>
            <div>
                <p class="text-white text-sm font-semibold flex items-center gap-2">
                    {{ $review->user->name ?? 'Usuario eliminado' }}
                    @if($isMyReview)
                        <span class="text-xs bg-amber-500/10 text-amber-400 border border-amber-500/20 px-2 py-0.5 rounded-full">Tú</span>
                    @endif
                    @if($review->user && $review->user->isAdmin())
                        <span class="text-xs bg-red-500/10 text-red-400 border border-red-500/20 px-2 py-0.5 rounded-full">Admin</span>
                    @endif
                </p>
                <p class="text-gray-500 text-xs">{{ $review->created_at->diffForHumans() }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-amber-400 text-sm font-bold whitespace-nowrap">⭐ {{ $review->rating }}/10</span>
            @if($canDeleteReview && !$isMyReview)
                {{-- Admin borra hilo por incumplimiento --}}
                <form action="{{ route('reviews.destroy', $review) }}" method="POST"
                      onsubmit="return confirm('¿Eliminar este hilo por incumplimiento de las normas?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-400 hover:text-red-300 text-xs" title="Borrar hilo (admin)">
                        🛡 Borrar hilo
                    </button>
                </form>
            @endif
        </div>
    </header>

    {{-- Cuerpo --}}
    @if($review->body)
        <p class="text-gray-300 text-sm leading-relaxed mb-4 whitespace-pre-line">{{ $review->body }}</p>
    @endif

    {{-- ===================== Comentarios (hilo Reddit-style) ===================== --}}
    @php
        // Agrupamos por parent_id; los root tienen parent_id NULL → usamos 0 como clave
        $commentsByParent = $review->comments->groupBy(fn($c) => $c->parent_id ?? 0);
        $rootComments    = $commentsByParent->get(0) ?? collect();
        $totalComments   = $review->comments->count();
    @endphp

    <div x-data="{ open: {{ $totalComments > 0 ? 'true' : 'false' }} }" class="mt-3 border-t border-gray-800 pt-3">
        <button type="button" @click="open = !open"
                class="text-gray-400 hover:text-amber-400 text-xs font-medium transition-colors flex items-center gap-1">
            <span x-text="open ? '▼' : '▶'"></span>
            Comentarios ({{ $totalComments }})
        </button>

        <div x-show="open" x-cloak class="mt-4 space-y-4">

            {{-- Árbol de comentarios --}}
            @foreach($rootComments as $rootComment)
                @include('books.partials.comment', [
                    'comment'          => $rootComment,
                    'commentsByParent' => $commentsByParent,
                    'review'           => $review,
                    'depth'            => 0,
                ])
            @endforeach

            {{-- Formulario nuevo comentario raíz --}}
            @auth
                <form action="{{ route('comments.store', $review) }}" method="POST" class="flex gap-2 pt-2 border-t border-gray-800/60">
                    @csrf
                    <input type="text" name="body" required maxlength="1000"
                           placeholder="Comenta este hilo..."
                           class="flex-1 bg-gray-800 border border-gray-700 text-white text-sm rounded-xl px-3 py-2 focus:outline-none focus:border-amber-500 placeholder-gray-600">
                    <button type="submit"
                            class="bg-amber-500 hover:bg-amber-400 text-gray-900 font-semibold text-sm px-4 py-2 rounded-xl transition-colors">
                        Enviar
                    </button>
                </form>
                @error('body') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
            @else
                <p class="text-gray-500 text-xs"><a href="{{ route('login') }}" class="text-amber-400 hover:text-amber-300">Inicia sesión</a> para comentar.</p>
            @endauth

        </div>
    </div>
</article>
