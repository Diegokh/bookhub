<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    public function store(Request $request, $bookId)
    {
        $book = Book::findOrFail($bookId);
        $user = $request->user();

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:10',
            'body'   => 'nullable|string|max:2000',
        ]);

        Review::updateOrCreate(
            ['book_id' => $book->id, 'user_id' => $user->id],
            ['rating' => $validated['rating'], 'body' => $validated['body'] ?? null]
        );

        // Marcar el libro como leído si no lo estaba
        if (! $user->hasRead($book)) {
            $user->readBooks()->attach($book->id, ['read_at' => now()]);
        }

        return back()->with('success', 'Reseña guardada.');
    }

    public function update(Request $request, Review $review)
    {
        $this->authorize('update', $review);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:10',
            'body'   => 'nullable|string|max:2000',
        ]);

        $review->update($validated);

        return back()->with('success', 'Reseña actualizada.');
    }

    public function destroy(Request $request, Review $review)
    {
        $this->authorize('delete', $review);

        $bookId = $review->book_id;
        $isAdmin = $request->user()->isAdmin() && $request->user()->id !== $review->user_id;

        $review->delete();

        $msg = $isAdmin
            ? 'Hilo eliminado por incumplimiento de normas.'
            : 'Reseña eliminada.';

        return redirect("/books/{$bookId}")->with('success', $msg);
    }
}
