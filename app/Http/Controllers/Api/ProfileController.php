<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\CreateProfileRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Auth::user()->organization->profiles()->with(['user:id,name'])->get(), 200);
    }

    public function store(CreateProfileRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $organization = Auth::user()->organization;

        return response()->json($organization->profiles()->create($payload), 201);
    }

    public function show(Profile $profile): JsonResponse
    {
        return response()->json($profile, 200);
    }

    public function update(Profile $profile, UpdateProfileRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $profile->fill($payload)->update();

        return response()->json([], 204);
    }

    public function destroy(Profile $profile): JsonResponse
    {
        $profile->delete();
        return response()->json([], 204);
    }
}
