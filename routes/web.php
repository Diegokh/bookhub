<?php

use App\Http\Controllers\Admin\OpenLibraryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReadListController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Libros — lista pública
Route::get('/books', [BookController::class, 'index'])->name('books.index');

// Libros — solo admin (van ANTES que /books/{id})
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{id}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{id}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{id}', [BookController::class, 'destroy'])->name('books.destroy');

    // Open Library proxy (sólo admin) — autocompletado del formulario
    Route::get('/admin/openlibrary/search', [OpenLibraryController::class, 'search'])->name('admin.openlibrary.search');
    Route::get('/admin/openlibrary/work',   [OpenLibraryController::class, 'work'])->name('admin.openlibrary.work');
});

// Acciones de usuario sobre libros (auth)
Route::middleware('auth')->group(function () {
    // Lista de leídos
    Route::get('/my-books', [ReadListController::class, 'index'])->name('my-books.index');
    Route::post('/books/{id}/read', [ReadListController::class, 'toggle'])->name('books.read.toggle');

    // Reseñas
    Route::post('/books/{id}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Comentarios
    Route::post('/reviews/{review}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// Libros — show pública (debe ir AL FINAL para no capturar "create")
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
