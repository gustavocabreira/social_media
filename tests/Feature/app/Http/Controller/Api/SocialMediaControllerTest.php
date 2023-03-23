<?php

namespace Tests\Feature\app\Http\Controller\Api;

use App\Models\SocialMedia;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class SocialMediaControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_it_should_create_a_new_social_media(): void
    {
        // arrange
        $payload = [
            'name' => 'Facebook',
            'icon' => 'facebook'
        ];

        // act
        $response = $this->actingAs($this->user)->postJson(route('social_medias.store'), $payload);

        // assert
        $response->assertCreated();
        $response->assertJsonStructure(['id', 'name', 'icon', 'created_at', 'updated_at']);
        $this->assertDatabaseHas('social_medias', [
            'id' => 1,
            ... $payload
        ]);
    }

    public function test_it_should_throw_a_validation_exception_when_providing_an_invalid_payload(): void
    {
        // assert
        $this->withoutExceptionHandling();
        $this->expectException(ValidationException::class);

        // arrange
        $payload = [
            'name' => null,
            'icon' => null
        ];

        // act
        $this->actingAs($this->user)->postJson(route('social_medias.store'), $payload);
    }

    public function test_it_should_throw_an_authentication_exception_when_using_an_unauthenticated_user(): void
    {
        // assert
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        // arrange
        $payload = [
            'name' => null,
            'icon' => null
        ];

        // act
        $this->postJson(route('social_medias.store'), $payload);
    }

    public function test_it_should_fetch_all_active_social_medias(): void
    {
        // arrange
        SocialMedia::factory()->create();

        // act
        $response = $this->actingAs($this->user)->getJson(route('social_medias.index'));

        // assert
        $response->assertOk();
        $this->assertCount(1, $response->json());
    }

    public function test_it_should_find_a_social_media_by_id(): void
    {
        // arrange
        $socialMedia = SocialMedia::factory()->create();

        // act
        $response = $this->actingAs($this->user)->getJson(route('social_medias.show', ['social_media' => $socialMedia->id]));

        // assert
        $response->assertOk();
        $response->assertJsonStructure(['id', 'name', 'icon', 'status', 'created_at', 'updated_at']);
    }

    public function test_it_should_throw_a_not_found_exception_when_providing_an_invalid_social_media(): void
    {
        // assert
        $this->withoutExceptionHandling();
        $this->expectException(ModelNotFoundException::class);

        // act
        $this->actingAs($this->user)->getJson(route('social_medias.show', ['social_media' => -1]));
    }

    public function test_it_should_delete_a_social_media(): void
    {
        // arrange
        $socialMedia = SocialMedia::factory()->create();

        // act
        $response = $this->actingAs($this->user)->deleteJson(route('social_medias.destroy', ['social_media' => $socialMedia->id]));

        // assert
        $response->assertNoContent();
        $this->assertDatabaseMissing('social_medias', [
            'id' => 1
        ]);
    }

    public function test_it_should_update_a_social_media(): void
    {
        // arrange
        $socialMedia = SocialMedia::factory()->create();
        $payload = [
            'name' => 'Twitter',
            'icon' => 'twitter',
            'status' => 0,
        ];

        // act
        $response = $this->actingAs($this->user)->putJson(route('social_medias.update', ['social_media' => $socialMedia->id]), $payload);

        // assert
        $response->assertNoContent();
        $this->assertDatabaseHas('social_medias', [
            'id' => 1,
            'name' => 'Twitter',
            'icon' => 'twitter',
            'status' => 0
        ]);
    }
}
