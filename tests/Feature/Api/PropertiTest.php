<?php

namespace Tests\Feature\Api;

use App\Models\Aturan;
use App\Models\Fasilitas;
use App\Models\Properti;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertiTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->owner()->create();
        $this->token = $this->owner->createToken('auth-token')->plainTextToken;
    }

    public function test_index_returns_all_properties(): void
    {
        Properti::factory(3)->create();

        $response = $this->getJson('/api/properti');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data'  => [],
                'meta'  => ['current_page', 'last_page', 'per_page', 'total'],
            ])
            ->assertJson(['success' => true]);
    }

    public function test_index_filters_by_kota(): void
    {
        Properti::factory()->create(['kota' => 'Jakarta']);
        Properti::factory()->create(['kota' => 'Bandung']);

        $response = $this->getJson('/api/properti?kota=Jakarta');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }

    public function test_index_filters_by_tipe_properti(): void
    {
        Properti::factory()->create(['tipe_properti' => 'villa']);
        Properti::factory()->create(['tipe_properti' => 'hotel']);

        $response = $this->getJson('/api/properti?tipe_properti=villa');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }

    public function test_index_filters_by_harga_range(): void
    {
        Properti::factory()->create(['harga_per_malam' => 100000]);
        Properti::factory()->create(['harga_per_malam' => 500000]);

        $response = $this->getJson('/api/properti?min_harga=200000&max_harga=600000');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }

    public function test_index_filters_by_max_tamu(): void
    {
        Properti::factory()->create(['max_tamu' => 2]);
        Properti::factory()->create(['max_tamu' => 6]);

        $response = $this->getJson('/api/properti?max_tamu=4');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }

    public function test_owner_can_create_property(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/properti', [
                'nama_properti'   => 'Villa Impian',
                'tipe_properti'   => 'villa',
                'deskripsi'       => 'Villa yang nyaman',
                'alamat'          => 'Jl. Raya No. 1',
                'kota'            => 'Bandung',
                'provinsi'        => 'Jawa Barat',
                'harga_per_malam' => 500000,
                'max_tamu'        => 6,
                'jumlah_ruang'    => 3,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Properti berhasil ditambahkan',
            ])
            ->assertJsonStructure([
                'data' => ['id_properti', 'nama_properti', 'tipe_properti', 'verified_status'],
            ]);

        $this->assertEquals('pending', $response->json('data.verified_status'));
    }

    public function test_unauthenticated_user_cannot_create_property(): void
    {
        $response = $this->postJson('/api/properti', [
            'nama_properti'   => 'Villa Impian',
            'tipe_properti'   => 'villa',
            'alamat'          => 'Jl. Raya No. 1',
            'kota'            => 'Bandung',
            'provinsi'        => 'Jawa Barat',
            'harga_per_malam' => 500000,
            'max_tamu'        => 6,
        ]);

        $response->assertStatus(401);
    }

    public function test_show_returns_property_detail(): void
    {
        $properti = Properti::factory()->create();

        $response = $this->getJson("/api/properti/{$properti->id_properti}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data'    => [
                    'id_properti' => $properti->id_properti,
                ],
            ]);
    }

    public function test_show_returns_404_for_nonexistent(): void
    {
        $response = $this->getJson('/api/properti/99999');

        $response->assertStatus(404);
    }

    public function test_owner_can_update_own_property(): void
    {
        $properti = Properti::factory()->create(['id_user' => $this->owner->id_user]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->putJson("/api/properti/{$properti->id_properti}", [
                'nama_properti' => 'Villa Impian Updated',
                'harga_per_malam' => 600000,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Properti berhasil diperbarui',
            ]);

        $this->assertEquals('Villa Impian Updated', $response->json('data.nama_properti'));
    }

    public function test_owner_cannot_update_others_property(): void
    {
        $otherOwner = User::factory()->owner()->create();
        $properti = Properti::factory()->create(['id_user' => $otherOwner->id_user]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->putJson("/api/properti/{$properti->id_properti}", [
                'nama_properti' => 'Hacked',
            ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke properti ini',
            ]);
    }

    public function test_owner_can_delete_own_property(): void
    {
        $properti = Properti::factory()->create(['id_user' => $this->owner->id_user]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->deleteJson("/api/properti/{$properti->id_properti}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Properti berhasil dihapus',
            ]);

        $this->assertDatabaseMissing('properti', ['id_properti' => $properti->id_properti]);
    }

    public function test_owner_cannot_delete_others_property(): void
    {
        $otherOwner = User::factory()->owner()->create();
        $properti = Properti::factory()->create(['id_user' => $otherOwner->id_user]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->deleteJson("/api/properti/{$properti->id_properti}");

        $response->assertStatus(403);
    }

    public function test_sync_fasilitas(): void
    {
        $properti = Properti::factory()->create(['id_user' => $this->owner->id_user]);
        $fasilitas = Fasilitas::factory(2)->create();

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson("/api/properti/{$properti->id_properti}/fasilitas/sync", [
                'id_fasilitas' => $fasilitas->pluck('id_fasilitas')->toArray(),
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Fasilitas berhasil disinkronkan',
            ]);

        $this->assertCount(2, $properti->fasilitas()->get());
    }

    public function test_sync_aturan(): void
    {
        $properti = Properti::factory()->create(['id_user' => $this->owner->id_user]);
        $aturan = Aturan::factory(2)->create();

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson("/api/properti/{$properti->id_properti}/aturan/sync", [
                'id_aturan' => $aturan->pluck('id_aturan')->toArray(),
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Aturan berhasil disinkronkan',
            ]);

        $this->assertCount(2, $properti->aturan()->get());
    }

    public function test_sync_fasilitas_unauthorized(): void
    {
        $otherOwner = User::factory()->owner()->create();
        $properti = Properti::factory()->create(['id_user' => $otherOwner->id_user]);
        $fasilitas = Fasilitas::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson("/api/properti/{$properti->id_properti}/fasilitas/sync", [
                'id_fasilitas' => [$fasilitas->id_fasilitas],
            ]);

        $response->assertStatus(403);
    }

    public function test_index_filters_by_id_user(): void
    {
        Properti::factory()->create(['id_user' => $this->owner->id_user]);
        $otherOwner = User::factory()->owner()->create();
        Properti::factory()->create(['id_user' => $otherOwner->id_user]);

        $response = $this->getJson('/api/properti?id_user=' . $this->owner->id_user);

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }
}
