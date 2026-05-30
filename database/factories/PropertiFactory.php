<?php

namespace Database\Factories;

use App\Models\Properti;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertiFactory extends Factory
{
    protected $model = Properti::class;

    public function definition(): array
    {
        $kota  = fake()->randomElement(['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Bali', 'Malang', 'Semarang']);
        $tipe  = fake()->randomElement(['hotel', 'villa', 'apartemen', 'kost', 'rumah', 'guesthouse']);

        return [
            'id_user'           => User::factory(),
            'nama_properti'     => fake()->words(3, true),
            'tipe_properti'     => $tipe,
            'deskripsi'         => fake()->paragraph(),
            'alamat'            => fake()->streetAddress(),
            'kota'              => $kota,
            'provinsi'          => 'Jawa Barat',
            'latitude'          => fake()->latitude(-8, -6),
            'longitude'         => fake()->longitude(105, 115),
            'harga_per_malam'   => fake()->numberBetween(100000, 5000000),
            'max_tamu'          => fake()->numberBetween(1, 16),
            'jumlah_ruang'      => fake()->numberBetween(1, 10),
            'status'            => 'active',
            'verified_status'   => 'verified',
        ];
    }
}
