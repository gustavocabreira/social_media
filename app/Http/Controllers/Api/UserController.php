<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendCreatedUserMailJob;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
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

        $user->assignRole('organization_admin');

        $token = $user->createToken('user:login');

        SendCreatedUserMailJob::dispatch($user);

        return response()->json([
            'access_token' => $token->accessToken->token,
        ], 201);
    }

    public function confirmEmail(Request $request): JsonResponse
    {
        $userId = Crypt::decrypt($request->input('user'));

        $user = User::findOrFail($userId);

        abort_if(!empty($user->email_verified_at), 422, 'E-mail já verificado!');

        $user->update([
            'email_verified_at' => now(),
        ]);

        Auth::loginUsingId($user->id);

        return response()->json([], 200);
    }
}
