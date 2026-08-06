<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Domains\User\Models\User;
use Database\Seeders\UserSeeder;
use Tests\ApiTestCase;

class AuthTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UserSeeder::class);
    }

    public function test_login_with_valid_credentials_returns_token(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@aguainmaculada.com',
            'password' => 'admin123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['token', 'user'],
            ]);
    }

    public function test_login_with_invalid_credentials_returns_error(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@aguainmaculada.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422);
    }

    public function test_register_creates_user_and_returns_token(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['token'],
            ]);
    }

    public function test_authenticated_user_can_access_profile(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'role' => 'Administrador del sistema',
        ]);
        $token = $user->createToken('auth-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/user');

        $response->assertStatus(200)
            ->assertJson([
                'data' => ['email' => $user->email],
            ]);
    }

    public function test_unauthenticated_user_cannot_access_profile(): void
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    }

    public function test_logout_revokes_token(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'role' => 'Administrador del sistema',
        ]);
        $token = $user->createToken('auth-token')->plainTextToken;

        $response = $this->postJson('/api/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'tokenable_type' => get_class($user),
        ]);
    }
}
