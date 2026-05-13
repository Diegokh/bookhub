<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Genre;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('genres')
            ->orderBy('rating', 'desc')
            ->paginate(12);

        return view('books.index', compact('books'));
    }

    public function show($id)
    {
        $book = Book::with(['genres', 'reviews'])->findOrFail($id);

        return view('books.show', compact('book'));
    }

    public function create()
    {
        $genres = Genre::orderBy('name')->get();

        return view('books.create', compact('genres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'nullable|string|max:255',
            'year'        => 'nullable|integer|min:1000|max:' . (date('Y') + 2),
            'rating'      => 'required|numeric|min:0|max:10',
            'description' => 'nullable|string|max:3000',
            'cover_url'   => 'nullable|url|max:500',
            'genres'      => 'nullable|array',
            'genres.*'    => 'exists:genres,id',
        ]);

        $book = Book::create($validated);

        if (!empty($validated['genres'])) {
            $book->genres()->sync($validated['genres']);
        }

        return redirect("/books/{$book->id}")
            ->with('success', 'Libro añadido correctamente.');
    }

    public function edit($id)
    {
        $book   = Book::with('genres')->findOrFail($id);
        $genres = Genre::orderBy('name')->get();

        return view('books.edit', compact('book', 'genres'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'author'      => 'nullable|string|max:255',
            'year'        => 'nullable|integer|min:1000|max:' . (date('Y') + 2),
            'rating'      => 'required|numeric|min:0|max:10',
            'description' => 'nullable|string|max:3000',
            'cover_url'   => 'nullable|url|max:500',
            'genres'      => 'nullable|array',
            'genres.*'    => 'exists:genres,id',
        ]);

        $book->update($validated);
        $book->genres()->sync($validated['genres'] ?? []);

        return redirect("/books/{$book->id}")
            ->with('success', 'Libro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return redirect('/books')
            ->with('success', 'Libro eliminado correctamente.');
    }
}
