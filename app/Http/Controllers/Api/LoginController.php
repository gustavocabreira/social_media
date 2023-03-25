<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if(!Auth::attempt($credentials)) {
            throw new \Exception('Invalid credentials', 403);
        }

        $token = Auth::user()->createToken('user:login');

        return response()->json([
            'access_token' => $token->accessToken->token,
        ], 200);
    }
}
