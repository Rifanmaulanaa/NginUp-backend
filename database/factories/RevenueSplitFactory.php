<?php

namespace Database\Factories;

use App\Models\Pemesanan;
use App\Models\RevenueSplit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RevenueSplitFactory extends Factory
{
    protected $model = RevenueSplit::class;

    public function definition(): array
    {
        $jumlahKotor = fake()->numberBetween(200000, 5000000);
        $persentase  = 10.00;

        return [
            'id_pesanan'               => Pemesanan::factory(),
            'id_user'                  => User::factory(),
            'jumlah_kotor'             => $jumlahKotor,
            'persentase_biaya_platform'=> $persentase,
            'jumlah_biaya_platform'    => $jumlahKotor * $persentase / 100,
            'jumlah_pemilik'           => $jumlahKotor * (100 - $persentase) / 100,
            'status'                   => fake()->randomElement(['pending', 'settled']),
            'settled_at'               => fake()->optional()->dateTimeThisMonth(),
        ];
    }
}
