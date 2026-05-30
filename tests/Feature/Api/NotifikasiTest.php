<?php

namespace Tests\Feature\Api;

use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotifikasiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('auth-token')->plainTextToken;
    }

    public function test_index_returns_notifications(): void
    {
        Notifikasi::factory(3)->create(['id_user' => $this->user->id_user]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson('/api/notifikasi');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
        $this->assertCount(3, $response->json('data'));
    }

    public function test_index_only_returns_own_notifications(): void
    {
        Notifikasi::factory()->create(['id_user' => $this->user->id_user]);
        $otherUser = User::factory()->create();
        Notifikasi::factory(2)->create(['id_user' => $otherUser->id_user]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson('/api/notifikasi');

        $this->assertCount(1, $response->json('data'));
    }

    public function test_mark_as_read(): void
    {
        $notif = Notifikasi::factory()->create([
            'id_user' => $this->user->id_user,
            'is_read' => false,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->putJson("/api/notifikasi/{$notif->id_notifikasi}/read");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Notifikasi ditandai sudah dibaca',
            ]);

        $this->assertTrue((bool) $notif->fresh()->is_read);
    }

    public function test_mark_all_as_read(): void
    {
        Notifikasi::factory(3)->create([
            'id_user' => $this->user->id_user,
            'is_read' => false,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->putJson('/api/notifikasi/read-all');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Semua notifikasi ditandai sudah dibaca',
            ]);

        $unreadCount = Notifikasi::where('id_user', $this->user->id_user)
            ->where('is_read', false)
            ->count();

        $this->assertEquals(0, $unreadCount);
    }

    public function test_mark_read_other_users_notification(): void
    {
        $otherUser = User::factory()->create();
        $notif = Notifikasi::factory()->create([
            'id_user' => $otherUser->id_user,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->putJson("/api/notifikasi/{$notif->id_notifikasi}/read");

        $response->assertStatus(404);
    }

    public function test_unauthenticated_user_cannot_access_notifications(): void
    {
        $response = $this->getJson('/api/notifikasi');
        $response->assertStatus(401);
    }
}
