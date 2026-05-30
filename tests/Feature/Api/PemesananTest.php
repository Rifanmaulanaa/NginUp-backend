<?php

namespace Tests\Feature\Api;

use App\Models\Pemesanan;
use App\Models\Properti;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PemesananTest extends TestCase
{
    use RefreshDatabase;

    private User $traveler;
    private User $owner;
    private Properti $properti;
    private string $travelerToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->traveler = User::factory()->create(['role' => 'traveler']);
        $this->travelerToken = $this->traveler->createToken('auth-token')->plainTextToken;
        $this->owner = User::factory()->owner()->create();
        $this->properti = Properti::factory()->create([
            'id_user'         => $this->owner->id_user,
            'harga_per_malam' => 200000,
            'max_tamu'        => 4,
        ]);
    }

    public function test_traveler_can_create_booking(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->travelerToken")
            ->postJson('/api/pemesanan', [
                'id_properti'       => $this->properti->id_properti,
                'tanggal_check_in'  => '2026-07-01',
                'tanggal_check_out' => '2026-07-03',
                'total_tamu'        => 2,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Pemesanan berhasil dibuat',
            ])
            ->assertJsonStructure([
                'data' => ['id_pesanan', 'status_pemesanan', 'total_harga', 'total_malam'],
            ]);

        $this->assertEquals('pending', $response->json('data.status_pemesanan'));
        $this->assertEquals(400000, $response->json('data.total_harga')); // 2 malam x 200k
        $this->assertEquals(2, $response->json('data.total_malam'));
    }

    public function test_owner_cannot_book_own_property(): void
    {
        $ownerToken = $this->owner->createToken('auth-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $ownerToken")
            ->postJson('/api/pemesanan', [
                'id_properti'       => $this->properti->id_properti,
                'tanggal_check_in'  => '2026-07-01',
                'tanggal_check_out' => '2026-07-03',
                'total_tamu'        => 2,
            ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Anda tidak bisa memesan properti sendiri',
            ]);
    }

    public function test_booking_exceeds_max_guests(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->travelerToken")
            ->postJson('/api/pemesanan', [
                'id_properti'       => $this->properti->id_properti,
                'tanggal_check_in'  => '2026-07-01',
                'tanggal_check_out' => '2026-07-03',
                'total_tamu'        => 10,
            ]);

        $response->assertStatus(422);
    }

    public function test_booking_overlap_is_rejected(): void
    {
        $this->withHeader('Authorization', "Bearer $this->travelerToken")
            ->postJson('/api/pemesanan', [
                'id_properti'       => $this->properti->id_properti,
                'tanggal_check_in'  => '2026-07-01',
                'tanggal_check_out' => '2026-07-05',
                'total_tamu'        => 2,
            ]);

        $response = $this->withHeader('Authorization', "Bearer $this->travelerToken")
            ->postJson('/api/pemesanan', [
                'id_properti'       => $this->properti->id_properti,
                'tanggal_check_in'  => '2026-07-03',
                'tanggal_check_out' => '2026-07-07',
                'total_tamu'        => 2,
            ]);

        $response->assertStatus(409)
            ->assertJson([
                'success' => false,
                'message' => 'Properti sudah dipesan di tanggal tersebut',
            ]);
    }

    public function test_traveler_can_list_own_bookings(): void
    {
        Pemesanan::factory(2)->create([
            'id_user'     => $this->traveler->id_user,
            'id_properti' => $this->properti->id_properti,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->travelerToken")
            ->getJson('/api/pemesanan');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
        $this->assertCount(2, $response->json('data'));
    }

    public function test_owner_can_list_bookings_for_own_properties(): void
    {
        $ownerToken = $this->owner->createToken('auth-token')->plainTextToken;
        Pemesanan::factory(2)->create([
            'id_properti' => $this->properti->id_properti,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $ownerToken")
            ->getJson('/api/pemesanan');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
        $this->assertCount(2, $response->json('data'));
    }

    public function test_traveler_cannot_see_others_bookings(): void
    {
        $otherTraveler = User::factory()->create();
        Pemesanan::factory()->create([
            'id_user'     => $otherTraveler->id_user,
            'id_properti' => $this->properti->id_properti,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->travelerToken")
            ->getJson('/api/pemesanan');

        $this->assertCount(0, $response->json('data'));
    }

    public function test_show_booking_detail(): void
    {
        $pemesanan = Pemesanan::factory()->create([
            'id_user'     => $this->traveler->id_user,
            'id_properti' => $this->properti->id_properti,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->travelerToken")
            ->getJson("/api/pemesanan/{$pemesanan->id_pesanan}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data'    => ['id_pesanan' => $pemesanan->id_pesanan],
            ]);
    }

    public function test_traveler_cannot_view_others_booking(): void
    {
        $otherTraveler = User::factory()->create();
        $pemesanan = Pemesanan::factory()->create([
            'id_user'     => $otherTraveler->id_user,
            'id_properti' => $this->properti->id_properti,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->travelerToken")
            ->getJson("/api/pemesanan/{$pemesanan->id_pesanan}");

        $response->assertStatus(403);
    }

    public function test_traveler_can_cancel_own_booking(): void
    {
        $pemesanan = Pemesanan::factory()->create([
            'id_user'          => $this->traveler->id_user,
            'id_properti'      => $this->properti->id_properti,
            'status_pemesanan' => 'pending',
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->travelerToken")
            ->putJson("/api/pemesanan/{$pemesanan->id_pesanan}/status", [
                'status_pemesanan' => 'cancelled',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Status pemesanan diperbarui',
            ]);
    }

    public function test_traveler_cannot_confirm_booking(): void
    {
        $pemesanan = Pemesanan::factory()->create([
            'id_user'          => $this->traveler->id_user,
            'id_properti'      => $this->properti->id_properti,
            'status_pemesanan' => 'pending',
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->travelerToken")
            ->putJson("/api/pemesanan/{$pemesanan->id_pesanan}/status", [
                'status_pemesanan' => 'confirmed',
            ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Aksi tidak diizinkan',
            ]);
    }

    public function test_unauthenticated_user_cannot_book(): void
    {
        $response = $this->postJson('/api/pemesanan', [
            'id_properti'       => $this->properti->id_properti,
            'tanggal_check_in'  => '2026-07-01',
            'tanggal_check_out' => '2026-07-03',
            'total_tamu'        => 2,
        ]);

        $response->assertStatus(401);
    }
}
