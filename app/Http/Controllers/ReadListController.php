<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class ReadListController extends Controller
{
    public function index(Request $request)
    {
        $books = $request->user()
            ->readBooks()
            ->with('genres')
            ->orderByPivot('read_at', 'desc')
            ->paginate(12);

        return view('books.my-books', compact('books'));
    }

    public function toggle(Request $request, $bookId)
    {
        $book = Book::findOrFail($bookId);
        $user = $request->user();

        if ($user->hasRead($book)) {
            $user->readBooks()->detach($book->id);
            $msg = 'Libro quitado de tu lista de leídos.';
        } else {
            $user->readBooks()->attach($book->id, ['read_at' => now()]);
            $msg = 'Libro añadido a tu lista de leídos.';
        }

        return back()->with('success', $msg);
    }
}
