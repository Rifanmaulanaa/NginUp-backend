<?php

namespace Tests\Feature\Api;

use App\Models\Pemesanan;
use App\Models\Properti;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    private User $traveler;
    private Properti $properti;
    private Pemesanan $pemesanan;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->traveler = User::factory()->create(['role' => 'traveler']);
        $this->token = $this->traveler->createToken('auth-token')->plainTextToken;
        $this->properti = Properti::factory()->create();
        $this->pemesanan = Pemesanan::factory()->create([
            'id_user'     => $this->traveler->id_user,
            'id_properti' => $this->properti->id_properti,
        ]);
    }

    public function test_index_returns_reviews_for_property(): void
    {
        Review::factory(2)->create([
            'id_properti' => $this->properti->id_properti,
        ]);

        $response = $this->getJson("/api/properti/{$this->properti->id_properti}/review");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
        $this->assertCount(2, $response->json('data'));
    }

    public function test_traveler_can_create_review(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/review', [
                'id_pesanan'  => $this->pemesanan->id_pesanan,
                'id_properti' => $this->properti->id_properti,
                'rating'      => 5,
                'komentar'    => 'Tempatnya bagus!',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Review berhasil ditambahkan',
            ]);
    }

    public function test_create_review_with_invalid_rating(): void
    {
        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->postJson('/api/review', [
                'id_pesanan'  => $this->pemesanan->id_pesanan,
                'id_properti' => $this->properti->id_properti,
                'rating'      => 6,
                'komentar'    => 'Bagus!',
            ]);

        $response->assertStatus(422);
    }

    public function test_traveler_can_delete_own_review(): void
    {
        $review = Review::factory()->create([
            'id_user'     => $this->traveler->id_user,
            'id_properti' => $this->properti->id_properti,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->deleteJson("/api/review/{$review->id_review}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Review berhasil dihapus',
            ]);
    }

    public function test_traveler_cannot_delete_others_review(): void
    {
        $otherTraveler = User::factory()->create();
        $review = Review::factory()->create([
            'id_user'     => $otherTraveler->id_user,
            'id_properti' => $this->properti->id_properti,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->deleteJson("/api/review/{$review->id_review}");

        $response->assertStatus(403);
    }

    public function test_traveler_can_update_own_review(): void
    {
        $review = Review::factory()->create([
            'id_user'     => $this->traveler->id_user,
            'id_properti' => $this->properti->id_properti,
            'rating'      => 3,
            'komentar'    => 'Biasa saja',
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->putJson("/api/review/{$review->id_review}", [
                'rating'   => 5,
                'komentar' => 'Ternyata bagus setelah diperbaiki!',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Review berhasil diperbarui',
            ]);

        $this->assertEquals(5, $review->fresh()->rating);
        $this->assertEquals('Ternyata bagus setelah diperbaiki!', $review->fresh()->komentar);
    }

    public function test_traveler_cannot_update_others_review(): void
    {
        $otherTraveler = User::factory()->create();
        $review = Review::factory()->create([
            'id_user'     => $otherTraveler->id_user,
            'id_properti' => $this->properti->id_properti,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->putJson("/api/review/{$review->id_review}", [
                'rating' => 5,
            ]);

        $response->assertStatus(403);
    }

    public function test_traveler_can_update_review_partially(): void
    {
        $review = Review::factory()->create([
            'id_user'     => $this->traveler->id_user,
            'id_properti' => $this->properti->id_properti,
            'rating'      => 4,
            'komentar'    => 'Mantap',
        ]);

        $response = $this->withHeader('Authorization', "Bearer $this->token")
            ->putJson("/api/review/{$review->id_review}", [
                'komentar' => 'Diedit komentar saja',
            ]);

        $response->assertStatus(200);
        $this->assertEquals(4, $review->fresh()->rating);
        $this->assertEquals('Diedit komentar saja', $review->fresh()->komentar);
    }

    public function test_unauthenticated_user_cannot_create_review(): void
    {
        $response = $this->postJson('/api/review', [
            'id_pesanan'  => $this->pemesanan->id_pesanan,
            'id_properti' => $this->properti->id_properti,
            'rating'      => 5,
        ]);

        $response->assertStatus(401);
    }
}
