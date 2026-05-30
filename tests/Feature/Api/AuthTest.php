<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_with_valid_data(): void
    {
        $response = $this->postJson('/api/register', [
            'nama'     => 'Test User',
            'username' => 'testuser',
            'email'    => 'test@example.com',
            'password' => 'password123',
            'no_hp'    => '08123456789',
            'role'     => 'traveler',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['user', 'token'],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Registrasi berhasil',
            ]);

        $this->assertDatabaseHas('users', [
            'email'    => 'test@example.com',
            'username' => 'testuser',
            'role'     => 'traveler',
        ]);
    }

    public function test_register_with_default_role(): void
    {
        $response = $this->postJson('/api/register', [
            'nama'     => 'Test User',
            'username' => 'testuser2',
            'email'    => 'test2@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'test2@example.com',
            'role'  => 'traveler',
        ]);
    }

    public function test_register_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'dup@example.com']);

        $response = $this->postJson('/api/register', [
            'nama'     => 'Dup User',
            'username' => 'dupuser',
            'email'    => 'dup@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422);
    }

    public function test_register_with_short_password(): void
    {
        $response = $this->postJson('/api/register', [
            'nama'     => 'Test',
            'username' => 'shortpw',
            'email'    => 'short@example.com',
            'password' => 'short',
        ]);

        $response->assertStatus(422);
    }

    public function test_login_with_valid_credentials(): void
    {
        User::factory()->create([
            'email'    => 'login@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'login@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['user', 'token'],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Login berhasil',
            ]);
    }

    public function test_login_with_wrong_password(): void
    {
        User::factory()->create([
            'email'    => 'wrong@example.com',
            'password' => bcrypt('correctpassword'),
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Email atau password salah',
            ]);
    }

    public function test_login_with_nonexistent_email(): void
    {
        $response = $this->postJson('/api/login', [
            'email'    => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401);
    }

    public function test_me_returns_authenticated_user(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/me');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data'    => [
                    'id_user'  => $user->id_user,
                    'nama'     => $user->nama,
                    'username' => $user->username,
                    'email'    => $user->email,
                    'role'     => $user->role,
                ],
            ]);
    }

    public function test_me_without_token(): void
    {
        $response = $this->getJson('/api/me');

        $response->assertStatus(401);
    }

    public function test_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Logout berhasil',
            ]);

        $this->assertCount(0, $user->tokens);
    }

    public function test_logout_without_token(): void
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }
}
