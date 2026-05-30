<?php

namespace Tests\Feature\Api;

use App\Models\KetersediaanProperti;
use App\Models\Properti;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KetersediaanTest extends TestCase
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

    public function test_index_returns_availability(): void
    {
        KetersediaanProperti::factory(2)->create([
            'id_properti' => $this->properti->id_properti,
        ]);

        $response = $this->getJson("/api/properti/{$this->properti->id_properti}/ketersediaan");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
        $this->assertCount(2, $response->json('data'));
    }

    public function test_owner_can_add_availability_block(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson("/api/properti/{$this->properti->id_properti}/ketersediaan", [
                'blocked_from'  => '2026-06-01',
                'blocked_until' => '2026-06-05',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Ketersediaan berhasil ditambahkan',
            ]);

        $this->assertDatabaseHas('ketersediaan_properti', [
            'id_properti' => $this->properti->id_properti,
        ]);
    }

    public function test_owner_cannot_add_availability_to_others_property(): void
    {
        $otherOwner = User::factory()->owner()->create();
        $otherToken = $otherOwner->createToken('auth-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $otherToken")
            ->postJson("/api/properti/{$this->properti->id_properti}/ketersediaan", [
                'blocked_from'  => '2026-06-01',
                'blocked_until' => '2026-06-05',
            ]);

        $response->assertStatus(403);
    }

    public function test_owner_can_delete_availability(): void
    {
        $ketersediaan = KetersediaanProperti::factory()->create([
            'id_properti' => $this->properti->id_properti,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->deleteJson(
                "/api/properti/{$this->properti->id_properti}/ketersediaan/{$ketersediaan->id_ketersediaan_properti}"
            );

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Blokir ketersediaan berhasil dihapus',
            ]);
    }
}
