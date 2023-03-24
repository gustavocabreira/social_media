<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Auth::user()->organization->profiles()->with(['user:id,name', 'socialMedia:id,name'])->get(), 200);
    }

    public function store(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'name' => ['required'],
        ]);

        $organization = Auth::user()->organization;

        return response()->json($organization->profiles()->create($payload), 201);
    }

    public function show(Profile $profile): JsonResponse
    {
        return response()->json($profile, 200);
    }

    public function update(Profile $profile, Request $request): JsonResponse
    {
        $payload = $request->validate([
            'name' => ['sometimes'],
        ]);

        $profile->fill($payload)->update();

        return response()->json([], 204);
    }

    public function destroy(Profile $profile): JsonResponse
    {
        $profile->delete();
        return response()->json([], 204);
    }
}
