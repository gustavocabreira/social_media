<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendCreatedUserMailJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    public function storeUser(Request $request): JsonResponse
    {
        $password = Str::random(16);

        $payload = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email:rfc,dns'],
        ]);

        $payload['password'] = Hash::make($password);

        $organization = Auth::user()->organization;
        $user = $organization->users()->create($payload);

        SendCreatedUserMailJob::dispatch($user);

        return response()->json($user, 201);
    }
}
