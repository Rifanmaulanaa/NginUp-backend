<?php

namespace Tests\Feature\Api;

use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Models\Properti;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PembayaranTest extends TestCase
{
    use RefreshDatabase;

    private User $traveler;
    private User $owner;
    private Properti $properti;
    private Pemesanan $pemesanan;
    private string $travelerToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->traveler = User::factory()->create(['role' => 'traveler']);
        $this->travelerToken = $this->traveler->createToken('auth-token')->plainTextToken;
        $this->owner = User::factory()->owner()->create();
        $this->properti = Properti::factory()->create(['id_user' => $this->owner->id_user]);
        $this->pemesanan = Pemesanan::factory()->create([
            'id_user'          => $this->traveler->id_user,
            'id_properti'      => $this->properti->id_properti,
            'status_pemesanan' => 'pending',
        ]);
    }

    public function test_traveler_can_create_payment(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->travelerToken")
            ->postJson("/api/pemesanan/{$this->pemesanan->id_pesanan}/pembayaran", [
                'code_pembayaran'   => 'INV-001',
                'jumlah_pembayaran' => 500000,
                'metode_pembayaran' => 'transfer',
                'bukti_pembayaran'  => 'https://example.com/bukti.jpg',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Pembayaran berhasil dikirim',
            ]);
    }

    public function test_other_traveler_cannot_create_payment(): void
    {
        $otherTraveler = User::factory()->create();
        $otherToken = $otherTraveler->createToken('auth-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $otherToken")
            ->postJson("/api/pemesanan/{$this->pemesanan->id_pesanan}/pembayaran", [
                'code_pembayaran'   => 'INV-002',
                'jumlah_pembayaran' => 500000,
                'metode_pembayaran' => 'transfer',
            ]);

        $response->assertStatus(403);
    }

    public function test_cannot_create_duplicate_payment(): void
    {
        Pembayaran::factory()->create([
            'id_pesanan' => $this->pemesanan->id_pesanan,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->travelerToken")
            ->postJson("/api/pemesanan/{$this->pemesanan->id_pesanan}/pembayaran", [
                'code_pembayaran'   => 'INV-003',
                'jumlah_pembayaran' => 500000,
                'metode_pembayaran' => 'transfer',
            ]);

        $response->assertStatus(409)
            ->assertJson([
                'success' => false,
                'message' => 'Pembayaran sudah ada',
            ]);
    }

    public function test_show_payment(): void
    {
        $pembayaran = Pembayaran::factory()->create([
            'id_pesanan' => $this->pemesanan->id_pesanan,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->travelerToken")
            ->getJson("/api/pemesanan/{$this->pemesanan->id_pesanan}/pembayaran/{$pembayaran->id_pembayaran}");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_owner_cannot_confirm_payment(): void
    {
        $pembayaran = Pembayaran::factory()->create([
            'id_pesanan'       => $this->pemesanan->id_pesanan,
            'status_pembayaran' => 'pending',
        ]);

        $ownerToken = $this->owner->createToken('auth-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $ownerToken")
            ->putJson(
                "/api/pemesanan/{$this->pemesanan->id_pesanan}/pembayaran/{$pembayaran->id_pembayaran}/konfirmasi",
                ['status_pembayaran' => 'paid']
            );

        $response->assertStatus(403);
    }

    public function test_owner_cannot_reject_payment(): void
    {
        $pembayaran = Pembayaran::factory()->create([
            'id_pesanan'       => $this->pemesanan->id_pesanan,
            'status_pembayaran' => 'pending',
        ]);

        $ownerToken = $this->owner->createToken('auth-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $ownerToken")
            ->putJson(
                "/api/pemesanan/{$this->pemesanan->id_pesanan}/pembayaran/{$pembayaran->id_pembayaran}/konfirmasi",
                ['status_pembayaran' => 'failed']
            );

        $response->assertStatus(403);
    }

    public function test_traveler_cannot_confirm_payment(): void
    {
        $pembayaran = Pembayaran::factory()->create([
            'id_pesanan'       => $this->pemesanan->id_pesanan,
            'status_pembayaran' => 'pending',
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->travelerToken")
            ->putJson(
                "/api/pemesanan/{$this->pemesanan->id_pesanan}/pembayaran/{$pembayaran->id_pembayaran}/konfirmasi",
                ['status_pembayaran' => 'paid']
            );

        $response->assertStatus(403);
    }
}
