<?php

namespace Tests\Feature\Api;

use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Models\Properti;
use App\Models\RevenueSplit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $owner;
    private User $traveler;
    private string $adminToken;
    private string $ownerToken;
    private string $travelerToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin    = User::factory()->create(['role' => 'admin', 'status' => 'active']);
        $this->owner    = User::factory()->owner()->create();
        $this->traveler = User::factory()->create(['role' => 'traveler']);

        $this->adminToken    = $this->admin->createToken('auth-token')->plainTextToken;
        $this->ownerToken    = $this->owner->createToken('auth-token')->plainTextToken;
        $this->travelerToken = $this->traveler->createToken('auth-token')->plainTextToken;
    }

    // ===========================
    // USER MANAGEMENT
    // ===========================

    public function test_admin_can_list_users(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
            ->getJson('/api/admin/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data'  => [],
                'meta'  => ['current_page', 'last_page', 'per_page', 'total'],
            ])
            ->assertJson(['success' => true]);
    }

    public function test_admin_can_filter_users_by_role(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
            ->getJson('/api/admin/users?role=owner');

        $response->assertStatus(200);
        foreach ($response->json('data') as $user) {
            $this->assertEquals('owner', $user['role']);
        }
    }

    public function test_admin_can_filter_users_by_status(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
            ->getJson('/api/admin/users?status=active');

        $response->assertStatus(200);
        foreach ($response->json('data') as $user) {
            $this->assertEquals('active', $user['status']);
        }
    }

    public function test_admin_can_search_users(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
            ->getJson('/api/admin/users?search=' . $this->traveler->nama);

        $response->assertStatus(200);
        $this->assertGreaterThanOrEqual(1, count($response->json('data')));
    }

    public function test_admin_can_show_user_detail(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
            ->getJson("/api/admin/users/{$this->traveler->id_user}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data'    => ['id_user' => $this->traveler->id_user],
            ]);
    }

    public function test_admin_can_update_user_status(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
            ->putJson("/api/admin/users/{$this->traveler->id_user}/status", [
                'status' => 'banned',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Status user berhasil diperbarui',
            ]);

        $this->assertEquals('banned', $this->traveler->fresh()->status);
    }

    public function test_admin_cannot_update_own_status(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
            ->putJson("/api/admin/users/{$this->admin->id_user}/status", [
                'status' => 'banned',
            ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Tidak bisa mengubah status akun sendiri',
            ]);
    }

    public function test_admin_can_delete_user(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
            ->deleteJson("/api/admin/users/{$this->traveler->id_user}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User berhasil dihapus',
            ]);

        $this->assertDatabaseMissing('users', ['id_user' => $this->traveler->id_user]);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
            ->deleteJson("/api/admin/users/{$this->admin->id_user}");

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Tidak bisa menghapus akun sendiri',
            ]);
    }

    public function test_non_admin_cannot_access_user_management(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->ownerToken")
            ->getJson('/api/admin/users');

        $response->assertStatus(403);
    }

    public function test_non_admin_cannot_delete_user(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->travelerToken")
            ->deleteJson("/api/admin/users/{$this->owner->id_user}");

        $response->assertStatus(403);
    }

    // ===========================
    // DASHBOARD
    // ===========================

    public function test_admin_can_view_dashboard(): void
    {
        Properti::factory(3)->create();
        Pemesanan::factory(2)->create();
        Pembayaran::factory()->create([
            'status_pembayaran' => 'paid',
            'jumlah_pembayaran' => 1000000,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
            ->getJson('/api/admin/dashboard');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'data' => [
                    'users'    => ['total', 'owner', 'traveler', 'admin'],
                    'properti' => ['total', 'verified', 'pending', 'rejected', 'active', 'draft'],
                    'bookings' => ['total', 'pending', 'confirmed', 'ongoing', 'completed', 'cancelled'],
                    'revenue'  => ['total_pembayaran', 'platform_fee', 'owner_payout', 'settled', 'pending'],
                ],
            ]);
    }

    public function test_non_admin_cannot_view_dashboard(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->ownerToken")
            ->getJson('/api/admin/dashboard');

        $response->assertStatus(403);
    }

    // ===========================
    // PROPERTI VERIFICATION
    // ===========================

    public function test_admin_can_verify_properti(): void
    {
        $properti = Properti::factory()->create([
            'id_user'         => $this->owner->id_user,
            'verified_status' => 'pending',
            'status'          => 'draft',
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
            ->putJson("/api/admin/properti/{$properti->id_properti}/verify", [
                'verified_status' => 'verified',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Status verifikasi properti diperbarui',
            ]);

        $properti->refresh();
        $this->assertEquals('verified', $properti->verified_status);
        $this->assertEquals('active', $properti->status);
    }

    public function test_admin_can_reject_properti(): void
    {
        $properti = Properti::factory()->create([
            'id_user'         => $this->owner->id_user,
            'verified_status' => 'pending',
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
            ->putJson("/api/admin/properti/{$properti->id_properti}/verify", [
                'verified_status' => 'rejected',
            ]);

        $response->assertStatus(200);

        $properti->refresh();
        $this->assertEquals('rejected', $properti->verified_status);
    }

    public function test_non_admin_cannot_verify_properti(): void
    {
        $properti = Properti::factory()->create(['id_user' => $this->owner->id_user]);

        $response = $this->withHeader('Authorization', "Bearer $this->ownerToken")
            ->putJson("/api/admin/properti/{$properti->id_properti}/verify", [
                'verified_status' => 'verified',
            ]);

        $response->assertStatus(403);
    }

    // ===========================
    // ADMIN DELETE PROPERTI
    // ===========================

    public function test_admin_can_delete_any_properti(): void
    {
        $properti = Properti::factory()->create(['id_user' => $this->owner->id_user]);

        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
            ->deleteJson("/api/admin/properti/{$properti->id_properti}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Properti berhasil dihapus',
            ]);

        $this->assertDatabaseMissing('properti', ['id_properti' => $properti->id_properti]);
    }

    // ===========================
    // ADMIN CONFIRM PAYMENT
    // ===========================

    public function test_admin_can_confirm_payment(): void
    {
        $properti  = Properti::factory()->create(['id_user' => $this->owner->id_user]);
        $pemesanan = Pemesanan::factory()->create([
            'id_user'          => $this->traveler->id_user,
            'id_properti'      => $properti->id_properti,
            'status_pemesanan' => 'pending',
        ]);
        $pembayaran = Pembayaran::factory()->create([
            'id_pesanan'       => $pemesanan->id_pesanan,
            'status_pembayaran' => 'pending',
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
            ->putJson(
                "/api/pemesanan/{$pemesanan->id_pesanan}/pembayaran/{$pembayaran->id_pembayaran}/konfirmasi",
                ['status_pembayaran' => 'paid']
            );

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Status pembayaran diperbarui',
            ]);

        $this->assertEquals('paid', $pembayaran->fresh()->status_pembayaran);
        $this->assertEquals('confirmed', $pemesanan->fresh()->status_pemesanan);
    }

    // ===========================
    // ADMIN BOOKING ACCESS
    // ===========================

    public function test_admin_can_see_all_bookings(): void
    {
        Pemesanan::factory(2)->create();

        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
            ->getJson('/api/pemesanan');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_admin_cannot_create_booking(): void
    {
        $properti = Properti::factory()->create(['id_user' => $this->owner->id_user]);

        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
            ->postJson('/api/pemesanan', [
                'id_properti'        => $properti->id_properti,
                'tanggal_check_in'   => '2026-07-01',
                'tanggal_check_out'  => '2026-07-03',
                'total_tamu'         => 2,
            ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Admin tidak bisa melakukan pemesanan',
            ]);
    }

    // ===========================
    // ADMIN DELETE REVIEW
    // ===========================

    public function test_admin_can_delete_any_review(): void
    {
        $pemesanan = Pemesanan::factory()->create(['id_user' => $this->traveler->id_user]);
        $review    = \App\Models\Review::factory()->create([
            'id_user'    => $this->traveler->id_user,
            'id_pesanan' => $pemesanan->id_pesanan,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
            ->deleteJson("/api/review/{$review->id_review}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Review berhasil dihapus',
            ]);

        $this->assertDatabaseMissing('review', ['id_review' => $review->id_review]);
    }

    // ===========================
    // ADMIN REVENUE SPLIT
    // ===========================

    public function test_admin_can_view_all_revenue_splits(): void
    {
        $pemesanan = Pemesanan::factory()->create(['id_user' => $this->traveler->id_user]);
        RevenueSplit::factory()->create([
            'id_pesanan' => $pemesanan->id_pesanan,
            'id_user'    => $this->owner->id_user,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->adminToken")
            ->getJson('/api/admin/revenue-split');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_owner_cannot_access_admin_revenue_split(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->ownerToken")
            ->getJson('/api/admin/revenue-split');

        $response->assertStatus(403);
    }
}
