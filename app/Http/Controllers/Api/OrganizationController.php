<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\CreateUserRequest;
use App\Jobs\SendCreatedUserMailJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    public function storeUser(CreateUserRequest $request): JsonResponse
    {
        // Check if the user is the organization admin
        abort_if(
            boolean: !Auth::user()->can('create user'),
            code: 403,
            message: 'You must be an administrator to create a new user.'
        );

        $password = Str::random(16);

        $payload = $request->validated();

        $payload['password'] = Hash::make($password);

        $organization = Auth::user()->organization;
        $user = $organization->users()->create($payload);

        SendCreatedUserMailJob::dispatch($user);

        return response()->json($user, 201);
    }
}
