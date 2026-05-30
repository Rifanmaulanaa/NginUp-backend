<?php

namespace Tests\Feature\Api;

use App\Models\Pemesanan;
use App\Models\Properti;
use App\Models\RevenueSplit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RevenueSplitTest extends TestCase
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

    public function test_owner_can_view_revenue_splits(): void
    {
        RevenueSplit::factory(3)->create(['id_user' => $this->owner->id_user]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson('/api/revenue-split');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
        $this->assertCount(3, $response->json('data'));
    }

    public function test_owner_only_sees_own_revenue(): void
    {
        RevenueSplit::factory()->create(['id_user' => $this->owner->id_user]);
        $otherOwner = User::factory()->owner()->create();
        RevenueSplit::factory(2)->create(['id_user' => $otherOwner->id_user]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->getJson('/api/revenue-split');

        $this->assertCount(1, $response->json('data'));
    }

    public function test_traveler_cannot_access_revenue(): void
    {
        $traveler = User::factory()->create(['role' => 'traveler']);
        $travelerToken = $traveler->createToken('auth-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $travelerToken")
            ->getJson('/api/revenue-split');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_access(): void
    {
        $response = $this->getJson('/api/revenue-split');
        $response->assertStatus(401);
    }
}
