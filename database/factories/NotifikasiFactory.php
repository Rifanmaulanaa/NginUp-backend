<?php

namespace Database\Factories;

use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotifikasiFactory extends Factory
{
    protected $model = Notifikasi::class;

    public function definition(): array
    {
        return [
            'id_user'  => User::factory(),
            'title'    => fake()->sentence(4),
            'pesan'    => fake()->paragraph(),
            'type'     => fake()->randomElement(['system', 'booking', 'payment']),
            'is_read'  => fake()->boolean(30),
        ];
    }
}
