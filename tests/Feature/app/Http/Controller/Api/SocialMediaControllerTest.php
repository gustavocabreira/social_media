<?php

namespace Tests\Feature\app\Http\Controller\Api;

use App\Models\Organization;
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
        $this->artisan('db:seed');
        Organization::factory()->create();
        $this->user = User::factory()->create();
    }

    public function test_it_should_create_a_new_social_media(): void
    {
        // Arrange
        $payload = [
            'name' => 'Facebook',
            'icon' => 'facebook'
        ];

        // Act
        $response = $this->actingAs($this->user)->postJson(route('social_medias.store'), $payload);

        // Assert
        $response->assertCreated();
        $response->assertJsonStructure(['id', 'name', 'icon', 'created_at', 'updated_at']);
        $this->assertDatabaseHas('social_medias', [
            'id' => 1,
            ... $payload
        ]);
    }

    public function test_it_should_throw_a_validation_exception_when_providing_an_invalid_payload(): void
    {
        // Assert
        $this->withoutExceptionHandling();
        $this->expectException(ValidationException::class);

        // Arrange
        $payload = [
            'name' => null,
            'icon' => null
        ];

        // Act
        $this->actingAs($this->user)->postJson(route('social_medias.store'), $payload);
    }

    public function test_it_should_throw_an_authentication_exception_when_using_an_unauthenticated_user(): void
    {
        // Assert
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);

        // Arrange
        $payload = [
            'name' => null,
            'icon' => null
        ];

        // Act
        $this->postJson(route('social_medias.store'), $payload);
    }

    public function test_it_should_fetch_all_active_social_medias(): void
    {
        // Arrange
        SocialMedia::factory()->create();

        // Act
        $response = $this->actingAs($this->user)->getJson(route('social_medias.index'));

        // Assert
        $response->assertOk();
        $this->assertCount(1, $response->json());
    }

    public function test_it_should_find_a_social_media_by_id(): void
    {
        // Arrange
        $socialMedia = SocialMedia::factory()->create();

        // Act
        $response = $this->actingAs($this->user)->getJson(route('social_medias.show', ['social_media' => $socialMedia->id]));

        // Assert
        $response->assertOk();
        $response->assertJsonStructure(['id', 'name', 'icon', 'status', 'created_at', 'updated_at']);
    }

    public function test_it_should_throw_a_not_found_exception_when_providing_an_invalid_social_media(): void
    {
        // Assert
        $this->withoutExceptionHandling();
        $this->expectException(ModelNotFoundException::class);

        // Act
        $this->actingAs($this->user)->getJson(route('social_medias.show', ['social_media' => -1]));
    }

    public function test_it_should_delete_a_social_media(): void
    {
        // Arrange
        $socialMedia = SocialMedia::factory()->create();

        // Act
        $response = $this->actingAs($this->user)->deleteJson(route('social_medias.destroy', ['social_media' => $socialMedia->id]));

        // Assert
        $response->assertNoContent();
        $this->assertDatabaseMissing('social_medias', [
            'id' => 1
        ]);
    }

    public function test_it_should_update_a_social_media(): void
    {
        // Arrange
        $socialMedia = SocialMedia::factory()->create();
        $payload = [
            'name' => 'Twitter',
            'icon' => 'twitter',
            'status' => 0,
        ];

        // Act
        $response = $this->actingAs($this->user)->putJson(route('social_medias.update', ['social_media' => $socialMedia->id]), $payload);

        // Assert
        $response->assertNoContent();
        $this->assertDatabaseHas('social_medias', [
            'id' => 1,
            'name' => 'Twitter',
            'icon' => 'twitter',
            'status' => 0
        ]);
    }
}
