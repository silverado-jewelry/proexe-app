<?php

namespace App\Http\Controllers;

use App\Movies\Services\MovieService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * @param MovieService $movieService
     */
    public function __construct(
        private MovieService $movieService
    ) {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getTitles(Request $request): JsonResponse
    {
        try {
            $titles = $this->movieService->getTitles();
        } catch (\Throwable $e) {
            return response()->json(['status' => 'failure'], 500);
        }

        return response()->json($titles);
    }
}
