<?php

namespace Tests\Feature\Api;

use App\Models\Fasilitas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FasilitasTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_fasilitas(): void
    {
        Fasilitas::factory(3)->create();

        $response = $this->getJson('/api/fasilitas');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
        $this->assertCount(3, $response->json('data'));
    }

    public function test_admin_can_create_fasilitas(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('auth-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/fasilitas', [
                'nama_fasilitas' => 'Kolam Renang',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Fasilitas berhasil ditambahkan',
            ]);

        $this->assertDatabaseHas('fasilitas', ['nama_fasilitas' => 'Kolam Renang']);
    }

    public function test_non_admin_cannot_create_fasilitas(): void
    {
        $user = User::factory()->create(['role' => 'owner']);
        $token = $user->createToken('auth-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/fasilitas', [
                'nama_fasilitas' => 'Kolam Renang',
            ]);

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_create_fasilitas(): void
    {
        $response = $this->postJson('/api/fasilitas', [
            'nama_fasilitas' => 'Kolam Renang',
        ]);

        $response->assertStatus(401);
    }

    public function test_admin_can_delete_fasilitas(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('auth-token')->plainTextToken;
        $fasilitas = Fasilitas::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->deleteJson("/api/fasilitas/{$fasilitas->id_fasilitas}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Fasilitas berhasil dihapus',
            ]);

        $this->assertDatabaseMissing('fasilitas', ['id_fasilitas' => $fasilitas->id_fasilitas]);
    }
}
