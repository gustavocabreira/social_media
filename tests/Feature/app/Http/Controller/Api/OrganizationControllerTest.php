<?php

namespace Tests\Feature\app\Http\Controller\Api;

use App\Jobs\SendCreatedUserMailJob;
use App\Models\Organization;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class OrganizationControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();
        $this->artisan('db:seed');

        Organization::factory()->create();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_it_should_be_able_to_create_a_new_user_that_belongs_to_the_organization(): void
    {
        // Arrange
        Queue::fake();

        $this->user->assignRole('organization_admin');

        $payload = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email(),
        ];

        // Act
        $response = $this->postJson(route('organizations.users.store'), $payload);

        // Assert
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'id' => 2,
            'organization_id' => 1,
            'email_verified_at' => null,
        ]);

        Queue::assertPushed(SendCreatedUserMailJob::class);
    }

    public function test_it_should_throw_an_exception_if_logged_user_is_not_an_admin_when_trying_to_create_a_new_user(): void
    {
        // Arrange
        $this->withoutExceptionHandling();
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('You must be an administrator to create a new user.');

        $payload = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email(),
        ];

        // Act
        $this->postJson(route('organizations.users.store'), $payload);
    }
}
