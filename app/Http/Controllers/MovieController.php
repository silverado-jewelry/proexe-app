<?php

namespace App\Http\Controllers;

use App\Movies\Services\MovieService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\StreamedJsonResponse;

class MovieController extends Controller
{
    /**
     * MovieController constructor.
     *
     * @param MovieService $movieService
     */
    public function __construct(
        private MovieService $movieService
    ) {
        // Uncomment the following line to restrict access to the getTitles method for non-authenticated users
//        $this->middleware('auth.external', ['only' => ['getTitles']]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getTitles(Request $request)
    {
        try {
            $response = new StreamedJsonResponse($this->movieService->getTitles());
        } catch (\Throwable $e) {
            return response()->json(['status' => 'failure'], 500);
        }

        return $response;
    }
}
