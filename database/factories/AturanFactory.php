<?php

namespace Database\Factories;

use App\Models\Aturan;
use Illuminate\Database\Eloquent\Factories\Factory;

class AturanFactory extends Factory
{
    protected $model = Aturan::class;

    public function definition(): array
    {
        return [
            'text_aturan' => fake()->sentence(),
        ];
    }
}
