<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * GET /api/movies
     */
    public function index(): JsonResponse
    {
        $movies = Movie::orderBy('score', 'desc')->paginate(10);

        return response()->json($movies);
    }

    /**
     * POST /api/movies
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'director' => 'nullable|string|max:255',
            'year'     => 'nullable|integer|min:1888|max:' . (date('Y') + 5),
            'score'    => 'required|numeric|min:0|max:10',
            'synopsis' => 'nullable|string|max:2000',
        ]);

        $movie = Movie::create($validated);

        return response()->json([
            'message' => 'Película creada correctamente',
            'data'    => $movie,
        ], 201);
    }

    /**
     * GET /api/movies/{id}
     */
    public function show(string $id): JsonResponse
    {
        $movie = Movie::find($id);

        if (! $movie) {
            return response()->json([
                'message' => 'Película no encontrada',
            ], 404);
        }

        return response()->json([
            'data' => $movie,
        ]);
    }

    /**
     * PUT/PATCH /api/movies/{id}
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $movie = Movie::find($id);

        if (! $movie) {
            return response()->json([
                'message' => 'Película no encontrada',
            ], 404);
        }

        $validated = $request->validate([
            'title'    => 'sometimes|required|string|max:255',
            'director' => 'nullable|string|max:255',
            'year'     => 'nullable|integer|min:1888|max:' . (date('Y') + 5),
            'score'    => 'sometimes|required|numeric|min:0|max:10',
            'synopsis' => 'nullable|string|max:2000',
        ]);

        $movie->update($validated);

        return response()->json([
            'message' => 'Película actualizada correctamente',
            'data'    => $movie,
        ]);
    }

    /**
     * DELETE /api/movies/{id}
     */
    public function destroy(string $id): JsonResponse
    {
        $movie = Movie::find($id);

        if (! $movie) {
            return response()->json([
                'message' => 'Película no encontrada',
            ], 404);
        }

        $movie->delete();

        return response()->json([
            'message' => 'Película borrada correctamente',
        ]);
    }
}