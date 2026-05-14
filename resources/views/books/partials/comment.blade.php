@php
    /**
     * Comentario tipo Reddit con rail vertical desde el avatar.
     * Variables esperadas:
     *   $comment            App\Models\Comment
     *   $commentsByParent   Collection agrupada por parent_id (0 = raíz)
     *   $review             App\Models\Review (para la URL del form)
     *   $depth              int (0 por defecto)
     */
    $depth       = $depth ?? 0;
    $children    = $commentsByParent[$comment->id] ?? collect();
    $hasChildren = $children->isNotEmpty();
    $childCount  = $children->count();
    $isMine      = auth()->check() && auth()->id() === $comment->user_id;
    $canDelete   = auth()->check() && auth()->user()->can('delete', $comment);
    $authorName  = $comment->user->name ?? 'Usuario eliminado';
    $isOp        = $comment->user_id === $review->user_id; // OP = autor de la reseña
@endphp

<div x-data="{ collapsed: false, replying: false }" class="comment-thread">
    <div class="flex gap-3">

        {{-- ============ RAIL (avatar + línea vertical + colapsador) ============ --}}
        <div class="flex flex-col items-center shrink-0">
            {{-- Avatar --}}
            <div class="w-7 h-7 rounded-full bg-gray-700 border border-gray-600 flex items-center justify-center text-gray-300 text-xs font-semibold shrink-0">
                {{ strtoupper(substr($authorName, 0, 1)) }}
            </div>

            {{-- Línea + botón colapsar (solo si tiene hijos) --}}
            @if($hasChildren)
                <button type="button" @click="collapsed = !collapsed"
                        class="flex-1 flex flex-col items-center w-7 mt-1.5 group cursor-pointer focus:outline-none"
                        :title="collapsed ? 'Expandir hilo' : 'Colapsar hilo'">
                    <span class="w-px flex-1 min-h-[12px] bg-gray-700 group-hover:bg-amber-500/80 transition-colors"></span>
                    <span class="w-4 h-4 rounded-full border border-gray-600 group-hover:border-amber-500 bg-gray-900 flex items-center justify-center text-gray-400 group-hover:text-amber-400 text-[11px] leading-none mt-1 transition-colors select-none"
                          x-text="collapsed ? '+' : '−'"></span>
                </button>
            @endif
        </div>

        {{-- ============ CONTENT ============ --}}
        <div class="flex-1 min-w-0 pb-2">

            {{-- Cabecera --}}
            <div class="flex items-center gap-1.5 text-xs flex-wrap">
                <span class="text-white font-semibold">{{ $authorName }}</span>
                @if($isOp)
                    <span class="bg-amber-500/15 text-amber-400 border border-amber-500/20 px-1.5 py-0.5 rounded-full text-[10px] font-semibold">OP</span>
                @endif
                @if($isMine)
                    <span class="text-amber-400 text-[11px]">(tú)</span>
                @endif
                @if($comment->user && $comment->user->isAdmin())
                    <span class="bg-red-500/15 text-red-400 border border-red-500/20 px-1.5 py-0.5 rounded-full text-[10px] font-semibold">Admin</span>
                @endif
                <span class="text-gray-500">· {{ $comment->created_at->diffForHumans() }}</span>
            </div>

            {{-- Cuerpo --}}
            <p class="text-gray-200 text-sm leading-snug whitespace-pre-line mt-1">{{ $comment->body }}</p>

            {{-- Acciones --}}
            <div class="flex items-center gap-3 mt-1 text-xs">
                @auth
                    <button type="button" @click="replying = !replying"
                            class="text-gray-500 hover:text-amber-400 font-medium transition-colors">
                        <span x-show="!replying">↩ Responder</span>
                        <span x-show="replying" x-cloak>Cancelar</span>
                    </button>
                @endauth

                @if($canDelete)
                    <form action="{{ route('comments.destroy', $comment) }}" method="POST"
                          onsubmit="return confirm('¿Borrar este comentario? Se eliminarán también las respuestas.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-500 hover:text-red-400 transition-colors">Borrar</button>
                    </form>
                @endif

                @if($hasChildren)
                    <button type="button" @click="collapsed = !collapsed"
                            class="text-gray-500 hover:text-amber-400 transition-colors font-medium">
                        <span x-show="!collapsed">▾ {{ $childCount }} {{ $childCount === 1 ? 'respuesta' : 'respuestas' }}</span>
                        <span x-show="collapsed" x-cloak>▸ Mostrar {{ $childCount }} {{ $childCount === 1 ? 'respuesta' : 'respuestas' }}</span>
                    </button>
                @endif
            </div>

            {{-- Form de respuesta inline --}}
            @auth
                <form x-show="replying" x-cloak
                      action="{{ route('comments.store', $review) }}" method="POST"
                      class="mt-2 flex gap-2">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <input type="text" name="body" required maxlength="1000"
                           x-init="$watch('replying', v => v && $nextTick(() => $el.focus()))"
                           placeholder="Responder a {{ $authorName }}..."
                           class="flex-1 bg-gray-800 border border-gray-700 text-white text-sm rounded-lg px-3 py-1.5 focus:outline-none focus:border-amber-500 placeholder-gray-600">
                    <button type="submit"
                            class="bg-amber-500 hover:bg-amber-400 text-gray-900 font-semibold text-xs px-3 py-1.5 rounded-lg transition-colors">
                        Enviar
                    </button>
                </form>
            @endauth

            {{-- Respuestas anidadas --}}
            @if($hasChildren)
                <div x-show="!collapsed" x-cloak class="mt-3 space-y-3">
                    @foreach($children as $child)
                        @include('books.partials.comment', [
                            'comment'          => $child,
                            'commentsByParent' => $commentsByParent,
                            'review'           => $review,
                            'depth'            => $depth + 1,
                        ])
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</div>
