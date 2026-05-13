<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OpenLibraryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OpenLibraryController extends Controller
{
    public function __construct(private OpenLibraryService $ol) {}

    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'q' => 'required|string|min:2|max:200',
        ]);

        return response()->json([
            'results' => $this->ol->search($validated['q']),
        ]);
    }

    public function work(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'regex:#^/works/OL\d+W$#'],
        ]);

        return response()->json($this->ol->getWork($validated['key']));
    }
}
