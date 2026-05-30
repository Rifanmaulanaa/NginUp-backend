<?php

namespace Database\Factories;

use App\Models\Gambar;
use App\Models\Properti;
use Illuminate\Database\Eloquent\Factories\Factory;

class GambarFactory extends Factory
{
    protected $model = Gambar::class;

    public function definition(): array
    {
        return [
            'id_properti' => Properti::factory(),
            'url_gambar'  => 'https://picsum.photos/seed/' . fake()->uuid() . '/800/600',
            'is_primary'  => false,
            'urutan'      => fake()->numberBetween(0, 10),
        ];
    }
}
