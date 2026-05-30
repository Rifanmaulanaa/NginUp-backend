<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama'       => fake()->name(),
            'username'   => fake()->unique()->userName(),
            'email'      => fake()->unique()->safeEmail(),
            'password'   => static::$password ??= Hash::make('password'),
            'no_hp'      => fake()->phoneNumber(),
            'foto_profil'=> null,
            'role'       => 'traveler',
            'status'     => 'active',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    public function owner(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'owner',
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
