<?php

namespace Database\Factories;

use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PembayaranFactory extends Factory
{
    protected $model = Pembayaran::class;

    public function definition(): array
    {
        return [
            'id_pesanan'        => Pemesanan::factory(),
            'code_pembayaran'   => 'INV-' . strtoupper(fake()->bothify('???-#####')),
            'jumlah_pembayaran' => fake()->numberBetween(200000, 5000000),
            'metode_pembayaran' => fake()->randomElement(['transfer', 'virtual_account', 'cash']),
            'bukti_pembayaran'  => fake()->optional()->url(),
            'status_pembayaran' => fake()->randomElement(['pending', 'paid', 'failed', 'refunded']),
            'tanggal_pembayaran'=> fake()->optional()->dateTimeThisMonth(),
            'expired_at'        => now()->addDays(1),
        ];
    }
}
