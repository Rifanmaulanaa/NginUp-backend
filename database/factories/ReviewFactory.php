<?php

namespace Database\Factories;

use App\Models\Pemesanan;
use App\Models\Properti;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'id_pesanan'  => Pemesanan::factory(),
            'id_user'     => User::factory(),
            'id_properti' => Properti::factory(),
            'rating'      => fake()->numberBetween(1, 5),
            'komentar'    => fake()->optional(0.8)->paragraph(),
        ];
    }
}
