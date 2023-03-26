<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\SocialMedia\CreateProfileSocialMediaRequest;
use App\Models\Profile;
use App\Models\ProfileSocialMedia;
use App\Models\SocialMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileSocialMediaController extends Controller
{
    public function store(Profile $profile, CreateProfileSocialMediaRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $response = ProfileSocialMedia::create([
            'profile_id' => $profile->id,
            'social_media_id' => $payload['social_media_id'],
        ]);

        return response()->json($response, 201);
    }

    public function destroy(Profile $profile, SocialMedia $socialMedia): JsonResponse
    {
        $profileSocialMedia = ProfileSocialMedia::query()
            ->where('profile_id', $profile->id)
            ->where('social_media_id', $socialMedia->id)
            ->firstOrFail();

        $profileSocialMedia->delete();

        return response()->json([], 204);
    }
}
