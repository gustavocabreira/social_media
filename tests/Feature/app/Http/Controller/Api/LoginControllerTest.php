<?php

namespace Tests\Feature\app\Http\Controller\Api;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_login_with_a_provided_email_and_password(): void
    {
        // Arrange
        $this->artisan('db:seed');
        Organization::factory()->create();
        $user = User::factory()->create();
        $payload = [
            'email' => $user->email,
            'password' => 'password'
        ];

        // Act
        $response = $this->postJson(route('login'), $payload);

        // Assert
        $response->assertOk();
        $response->assertJsonStructure(['access_token']);
    }
}
