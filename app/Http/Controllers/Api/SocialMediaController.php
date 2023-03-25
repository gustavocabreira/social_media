<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialMedia\CreateSocialMediaRequest;
use App\Http\Requests\SocialMedia\UpdateSocialMediaRequest;
use App\Models\SocialMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(SocialMedia::orderBy('name')->get(), 200);
    }

    public function store(CreateSocialMediaRequest $request): JsonResponse
    {
        $payload = $request->validated();
        return response()->json(SocialMedia::create($payload), 201);
    }

    public function show(SocialMedia $socialMedia): JsonResponse
    {
        return response()->json($socialMedia, 200);
    }

    public function update(SocialMedia $socialMedia, UpdateSocialMediaRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $socialMedia->fill($payload)->update();

        return response()->json([], 204);
    }

    public function destroy(SocialMedia $socialMedia): JsonResponse
    {
        $socialMedia->delete();
        return response()->json([], 204);
    }
}
