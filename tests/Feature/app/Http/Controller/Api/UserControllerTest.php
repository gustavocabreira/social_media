<?php

namespace Tests\Feature\app\Http\Controller\Api;

use App\Jobs\SendCreatedUserMailJob;
use App\Models\Organization;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();
        $this->artisan('db:seed');
    }

    public function test_it_should_create_a_new_user(): void
    {
        Queue::fake();

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

        // Assert that the created user has the organization admin role
        $this->assertTrue((User::find(1))->hasRole('organization_admin'));

        Queue::assertPushed(SendCreatedUserMailJob::class);
    }

    public function test_it_should_verify_the_email(): void
    {
        // Arrange
        $this->freezeTime(); // freezes time to compare
        Organization::factory()->create();
        $user = User::factory()->create();

        $urlSigned = URL::temporarySignedRoute(
            name: 'web.confirm-email',
            expiration: now()->addMinutes(5),
            parameters: ['user' => Crypt::encrypt($user->id)]
        );

        // Act
        $response = $this->getJson($urlSigned);

        // Assert
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id' => 1,
            'email_verified_at' => now(),
        ]);
        $this->assertInstanceOf(User::class, Auth::user());
    }

    public function test_it_should_throw_an_exception_when_trying_to_confirm_an_email_that_is_already_confirmed(): void
    {
        // Assert
        $this->withoutExceptionHandling();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('E-mail já verificado!');

        // Arrange
        Organization::factory()->create();
        $user = User::factory()->create([
            'email_verified_at' => now()
        ]);

        $urlSigned = URL::temporarySignedRoute(
            name: 'web.confirm-email',
            expiration: now()->addMinutes(5),
            parameters: ['user' => Crypt::encrypt($user->id)]
        );

        // Act
        $this->getJson($urlSigned);
    }
}
