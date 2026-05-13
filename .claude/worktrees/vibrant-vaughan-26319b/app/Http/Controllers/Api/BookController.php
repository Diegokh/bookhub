<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(): JsonResponse
    {
        $books = Book::with('genres')
            ->orderBy('rating', 'desc')
            ->paginate(15);

        return response()->json($books);
    }

    public function show($id): JsonResponse
    {
        $book = Book::with(['genres', 'reviews'])->findOrFail($id);

        return response()->json($book);
    }

    public function store(Request $request): JsonResponse
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'nullable|string|max:255',
            'year'        => 'nullable|integer|min:1000|max:' . (date('Y') + 2),
            'rating'      => 'required|numeric|min:0|max:10',
            'description' => 'nullable|string|max:3000',
            'cover_url'   => 'nullable|url|max:500',
            'published'   => 'boolean',
            'genres'      => 'nullable|array',
            'genres.*'    => 'exists:genres,id',
        ]);

        $book = Book::create($validated);

        if (!empty($validated['genres'])) {
            $book->genres()->sync($validated['genres']);
        }

        return response()->json($book->load('genres'), 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'nullable|string|max:255',
            'year'        => 'nullable|integer|min:1000|max:' . (date('Y') + 2),
            'rating'      => 'required|numeric|min:0|max:10',
            'description' => 'nullable|string|max:3000',
            'cover_url'   => 'nullable|url|max:500',
            'published'   => 'boolean',
            'genres'      => 'nullable|array',
            'genres.*'    => 'exists:genres,id',
        ]);

        $book->update($validated);
        $book->genres()->sync($validated['genres'] ?? []);

        return response()->json($book->load('genres'));
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json(['message' => 'Libro eliminado correctamente.']);
    }
}
