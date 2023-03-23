<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(SocialMedia::orderBy('name')->get(), 200);
    }

    public function store(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'name' => ['required'],
            'icon' => ['required']
        ]);

        return response()->json(SocialMedia::create($payload), 201);
    }

    public function show(SocialMedia $socialMedia): JsonResponse
    {
        return response()->json($socialMedia, 200);
    }

    public function update(SocialMedia $socialMedia, Request $request): JsonResponse
    {
        $payload = $request->validate([
            'name' => ['sometimes'],
            'icon' => ['sometimes'],
            'status' => ['sometimes']
        ]);

        $socialMedia->fill($payload)->update();

        return response()->json([], 204);
    }

    public function destroy(SocialMedia $socialMedia): JsonResponse
    {
        $socialMedia->delete();
        return response()->json([], 204);
    }
}
