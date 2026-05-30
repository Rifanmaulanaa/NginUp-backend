<?php

namespace Database\Factories;

use App\Models\KetersediaanProperti;
use App\Models\Properti;
use Illuminate\Database\Eloquent\Factories\Factory;

class KetersediaanPropertiFactory extends Factory
{
    protected $model = KetersediaanProperti::class;

    public function definition(): array
    {
        return [
            'id_properti'          => Properti::factory(),
            'blocked_from'         => fake()->date(),
            'blocked_until'        => fake()->date(),
            'status_ketersediaan'  => 'blocked',
        ];
    }
}
