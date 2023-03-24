<?php

namespace Tests\Feature\app\Http\Controller\Api;

use App\Models\Organization;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    public function setUp(): void
    {
        parent::setUp();
        Organization::factory()->create();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_it_should_fetch_all_organizations_profiles(): void
    {
        // arrange
        $profile = Profile::factory()->create();

        // act
        $response = $this->get(route('profiles.index'));

        // assert
        $response->assertOk();
        $response->assertJsonCount(1);
        $this->assertCount(1, $response->json());
    }

    public function test_it_should_create_a_new_profile(): void
    {
        // arrange
        $payload = [
            'name' => 'Perfil teste',
        ];

        // act
        $response = $this->postJson(route('profiles.store'), $payload);

        // assert
        $response->assertCreated();
        $this->assertDatabaseHas('profiles', [
            'id' => 1,
            'organization_id' => 1,
            'user_id' => 1,
            'name' => $payload['name'],
        ]);
    }

    public function test_it_should_find_a_profile(): void
    {
        // arrange
        $profile = Profile::factory()->create();

        // act
        $response = $this->getJson(route('profiles.show', ['profile' => $profile->id]));

        // assert
        $response->assertOk();
        $response->assertJsonStructure(['id', 'name']);
    }

    public function test_it_update_a_profile(): void
    {
        // arrange
        $profile = Profile::factory()->create();
        $payload = [
            'name' => 'Updated profile',
        ];

        // act
        $response = $this->putJson(route('profiles.update', ['profile' => $profile->id]), $payload);

        // assert
        $response->assertNoContent();
        $this->assertDatabaseHas('profiles', [
            'id' => 1,
            'name' => $payload['name'],
        ]);
    }

    public function test_it_should_delete_a_profile(): void
    {
        // arrange
        $profile = Profile::factory()->create();

        // act
        $response = $this->deleteJson(route('profiles.show', ['profile' => $profile->id]));

        // assert
        $response->assertNoContent();
        $this->assertDatabaseMissing('profiles', [
            'id' => 1,
        ]);
    }
}
