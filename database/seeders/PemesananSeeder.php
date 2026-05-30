<?php

namespace Database\Seeders;

use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Models\Properti;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PemesananSeeder extends Seeder
{
    public function run(): void
    {
        $travelers  = User::where('role', 'traveler')->get();
        $propertis  = Properti::all();

        foreach ($propertis as $properti) {
            foreach ($travelers as $traveler) {
                $checkIn  = Carbon::today()->subDays(rand(10, 30));
                $checkOut = (clone $checkIn)->addDays(rand(1, 3));
                $totalMalam = $checkIn->diffInDays($checkOut);

                $pemesanan = Pemesanan::create([
                    'id_user'           => $traveler->id_user,
                    'id_properti'       => $properti->id_properti,
                    'tanggal_check_in'  => $checkIn->format('Y-m-d'),
                    'tanggal_check_out' => $checkOut->format('Y-m-d'),
                    'total_malam'       => $totalMalam,
                    'total_tamu'        => rand(1, $properti->max_tamu),
                    'total_harga'       => $totalMalam * $properti->harga_per_malam,
                    'status_pemesanan'  => 'completed',
                    'catatan_traveler'  => 'Terima kasih, pelayanan memuaskan!',
                ]);

                Pembayaran::create([
                    'id_pesanan'        => $pemesanan->id_pesanan,
                    'code_pembayaran'   => 'INV-' . strtoupper(fake()->bothify('???-#####')),
                    'jumlah_pembayaran' => $pemesanan->total_harga,
                    'metode_pembayaran' => 'transfer',
                    'bukti_pembayaran'  => null,
                    'status_pembayaran' => 'paid',
                    'tanggal_pembayaran'=> $checkIn,
                    'expired_at'        => $checkIn->subDay(),
                ]);

                Review::create([
                    'id_pesanan'  => $pemesanan->id_pesanan,
                    'id_user'     => $traveler->id_user,
                    'id_properti' => $properti->id_properti,
                    'rating'      => rand(3, 5),
                    'komentar'    => 'Penginapan yang nyaman dan bersih. Recommended!',
                ]);
            }
        }
    }
}
