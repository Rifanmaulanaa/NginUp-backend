<?php

namespace Tests\Feature\Api;

use App\Models\Aturan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AturanTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_aturan(): void
    {
        Aturan::factory(3)->create();

        $response = $this->getJson('/api/aturan');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
        $this->assertCount(3, $response->json('data'));
    }

    public function test_admin_can_create_aturan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('auth-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/aturan', [
                'text_aturan' => 'Dilarang merokok',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Aturan berhasil ditambahkan',
            ]);

        $this->assertDatabaseHas('aturan', ['text_aturan' => 'Dilarang merokok']);
    }

    public function test_non_admin_cannot_create_aturan(): void
    {
        $user = User::factory()->create(['role' => 'traveler']);
        $token = $user->createToken('auth-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/aturan', [
                'text_aturan' => 'Dilarang merokok',
            ]);

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_create_aturan(): void
    {
        $response = $this->postJson('/api/aturan', [
            'text_aturan' => 'Dilarang merokok',
        ]);

        $response->assertStatus(401);
    }

    public function test_admin_can_delete_aturan(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('auth-token')->plainTextToken;
        $aturan = Aturan::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/aturan/{$aturan->id_aturan}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Aturan berhasil dihapus',
            ]);

        $this->assertDatabaseMissing('aturan', ['id_aturan' => $aturan->id_aturan]);
    }
}
