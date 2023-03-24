<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $payload = $request->only('name', 'email', 'password');
        $payload['password'] = Hash::make($payload['password']);

        $organization = Organization::create([
            'name' => 'Nova Organização',
        ]);

        $user = $organization->users()->create($payload);

        $token = $user->createToken('user:login');

        return response()->json([
            'access_token' => $token->accessToken->token,
        ], 201);
    }
}
