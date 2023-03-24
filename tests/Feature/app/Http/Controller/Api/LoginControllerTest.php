<?php

namespace Tests\Feature\app\Http\Controller\Api;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_login_with_a_provided_email_and_password(): void
    {
        Organization::factory()->create();
        $user = User::factory()->create();
        $payload = [
            'email' => $user->email,
            'password' => 'password'
        ];

        $response = $this->postJson(route('login'), $payload);

        $response->assertOk();
        $response->assertJsonStructure(['access_token']);
    }
}
