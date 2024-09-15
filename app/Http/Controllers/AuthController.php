<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
//        $this->middleware('guest.external', ['only' => ['login']]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        if (auth('external')->validate($request->only(['login', 'password']))) {
            $user = auth('external')->user();

            return response()->json([
                'status' => 'success',
                'token' => $user->token
            ]);
        }

        return response()->json([
            'status' => 'failure',
        ], 401);
    }
}
