<?php

namespace Tests\Feature\app\Http\Controller\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_create_a_new_user(): void
    {
        $payload = User::factory()->make()->toArray();
        $payload['password'] = '12345678';
        $response = $this->postJson(route('users.store'), $payload);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'id' => 1,
            'email' => $payload['email'],
            'organization_id' => 1,
        ]);

        $this->assertDatabaseHas('organizations', [
            'id' => 1,
            'name' => 'Nova Organização',
        ]);

        $response->assertJsonStructure(['access_token']);
    }
}
