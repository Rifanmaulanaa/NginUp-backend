<?php

namespace Database\Factories;

use App\Models\Pemesanan;
use App\Models\Properti;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PemesananFactory extends Factory
{
    protected $model = Pemesanan::class;

    public function definition(): array
    {
        $checkIn  = Carbon::today()->addDays(fake()->numberBetween(1, 30));
        $checkOut = (clone $checkIn)->addDays(fake()->numberBetween(1, 7));

        return [
            'id_user'           => User::factory(),
            'id_properti'       => Properti::factory(),
            'tanggal_check_in'  => $checkIn->format('Y-m-d'),
            'tanggal_check_out' => $checkOut->format('Y-m-d'),
            'total_malam'       => $checkIn->diffInDays($checkOut),
            'total_tamu'        => fake()->numberBetween(1, 4),
            'total_harga'       => fake()->numberBetween(200000, 5000000),
            'status_pemesanan'  => fake()->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
            'catatan_traveler'  => fake()->optional()->sentence(),
        ];
    }
}
