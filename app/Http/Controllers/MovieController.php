<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::orderBy('score', 'desc')
            ->paginate(10);

        return view('movies.index', compact('movies'));
    }

    public function show($id)
    {
        $movie = Movie::findOrFail($id);

        return view('movies.show', compact('movie'));
    }

    public function create()
    {
        return view('movies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'director' => 'nullable|string|max:255',
            'year'     => 'nullable|integer|min:1888|max:' . (date('Y') + 5),
            'score'    => 'required|numeric|min:0|max:10',
            'synopsis' => 'nullable|string|max:2000',
        ]);

        $movie = Movie::create($validated);

        return redirect("/movies/{$movie->id}")
            ->with('success', 'Película creada correctamente');
    }

    public function edit($id)
    {
        $movie = Movie::findOrFail($id);

        return view('movies.edit', compact('movie'));
    }

    public function update(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);

        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'director' => 'nullable|string|max:255',
            'year'     => 'nullable|integer|min:1888|max:' . (date('Y') + 5),
            'score'    => 'required|numeric|min:0|max:10',
            'synopsis' => 'nullable|string|max:2000',
        ]);

        $movie->update($validated);

        return redirect("/movies/{$movie->id}")
            ->with('success', 'Película actualizada correctamente');
    }

    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);
        $movie->delete();

        return redirect('/movies')
            ->with('success', 'Película borrada correctamente');
    }
}