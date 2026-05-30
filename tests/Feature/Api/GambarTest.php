<?php

namespace Tests\Feature\Api;

use App\Models\Gambar;
use App\Models\Properti;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GambarTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private Properti $properti;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->owner()->create();
        $this->token = $this->owner->createToken('auth-token')->plainTextToken;
        $this->properti = Properti::factory()->create(['id_user' => $this->owner->id_user]);
    }

    public function test_index_returns_images_for_property(): void
    {
        Gambar::factory(2)->create(['id_properti' => $this->properti->id_properti]);

        $response = $this->getJson("/api/properti/{$this->properti->id_properti}/gambar");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
        $this->assertCount(2, $response->json('data'));
    }

    public function test_owner_can_add_image(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson("/api/properti/{$this->properti->id_properti}/gambar", [
                'url_gambar' => 'https://example.com/image.jpg',
                'is_primary' => true,
                'urutan'     => 1,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Gambar berhasil ditambahkan',
            ]);

        $this->assertDatabaseHas('gambar', [
            'id_properti' => $this->properti->id_properti,
            'url_gambar'  => 'https://example.com/image.jpg',
            'is_primary'  => 1,
        ]);
    }

    public function test_owner_cannot_add_image_to_others_property(): void
    {
        $otherOwner = User::factory()->owner()->create();
        $otherToken = $otherOwner->createToken('auth-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $otherToken")
            ->postJson("/api/properti/{$this->properti->id_properti}/gambar", [
                'url_gambar' => 'https://example.com/image.jpg',
            ]);

        $response->assertStatus(403);
    }

    public function test_owner_can_update_image(): void
    {
        $gambar = Gambar::factory()->create([
            'id_properti' => $this->properti->id_properti,
            'is_primary'  => false,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->putJson("/api/properti/{$this->properti->id_properti}/gambar/{$gambar->id_gambar}", [
                'url_gambar' => 'https://example.com/updated.jpg',
                'is_primary' => true,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Gambar berhasil diperbarui',
            ]);
    }

    public function test_owner_can_delete_image(): void
    {
        $gambar = Gambar::factory()->create(['id_properti' => $this->properti->id_properti]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->deleteJson("/api/properti/{$this->properti->id_properti}/gambar/{$gambar->id_gambar}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Gambar berhasil dihapus',
            ]);

        $this->assertDatabaseMissing('gambar', ['id_gambar' => $gambar->id_gambar]);
    }
}
