<?php

namespace Tests\Feature\app\Http\Controller\Api;

use App\Models\Organization;
use App\Models\Profile;
use App\Models\ProfileSocialMedia;
use App\Models\SocialMedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileSocialMediaControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private SocialMedia $socialMedia;
    private Profile $profile;

    protected function setUp(): void
    {
        parent::setUp();
        Organization::factory()->create();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->profile = Profile::factory()->create();
        $this->socialMedia = SocialMedia::factory()->create();
    }

    public function test_it_should_create_a_social_media_to_a_selected_profile(): void
    {
        // Arrange
        $payload = [
            'social_media_id' => $this->socialMedia->id,
        ];

        // Act
        $response = $this->post(route('profiles.social-medias.store', [
            'profile' => $this->profile->id,
        ]), $payload);

        // Assert
        $response->assertStatus(201);
        $this->assertDatabaseHas('profiles_social_medias', [
            'profile_id' => $this->profile->id,
            'social_media_id' => $this->socialMedia->id,
        ]);
    }

    public function test_it_should_be_able_to_remove_a_social_media_from_a_profile()
    {
        // Arrange
        ProfileSocialMedia::create([
            'profile_id' => $this->profile->id,
            'social_media_id' => $this->socialMedia->id,
        ]);

        // Act
        $response = $this->delete(route('profiles.social-medias.destroy', [
            'profile' => $this->profile->id,
            'social_media' => $this->socialMedia->id,
        ]));

        // Assert
        $response->assertNoContent();
        $this->assertDatabaseMissing('profiles_social_medias', [
            'profile_id' => $this->profile->id,
            'social_media_id' => $this->socialMedia->id,
        ]);
    }
}
