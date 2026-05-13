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
            'body' => 'required|string|max:1000',
        ]);

        $review->comments()->create([
            'user_id' => $request->user()->id,
            'body'    => $validated['body'],
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
