<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Review;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Review $review)
    {
        $validated = $request->validate([
            'body'      => 'required|string|max:1000',
            'parent_id' => 'nullable|integer|exists:comments,id',
        ]);

        // Si hay parent, validar que pertenece a la misma review (evita cross-thread reply)
        if (!empty($validated['parent_id'])) {
            $parent = Comment::find($validated['parent_id']);
            if (! $parent || $parent->review_id !== $review->id) {
                return back()->withErrors(['parent_id' => 'Respuesta inválida.']);
            }
        }

        $review->comments()->create([
            'user_id'   => $request->user()->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'body'      => $validated['body'],
        ]);

        return back()->with('success', 'Comentario publicado.');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return back()->with('success', 'Comentario eliminado.');
    }
}
