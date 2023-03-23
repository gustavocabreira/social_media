<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email:rfc,dns'],
            'password' => ['required'],
        ]);

        if(!Auth::attempt($credentials)) {
            throw new \Exception('Invalid credentials', 403);
        }

        $token = Auth::user()->createToken('user:login');

        return response()->json([
            'access_token' => $token->accessToken->token,
        ], 200);
    }
}
