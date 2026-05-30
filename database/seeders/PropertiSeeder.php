<?php

namespace Database\Seeders;

use App\Models\Fasilitas;
use App\Models\Gambar;
use App\Models\Properti;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertiSeeder extends Seeder
{
    public function run(): void
    {
        $owners = User::where('role', 'owner')->get();
        $fasilitasIds = Fasilitas::pluck('id_fasilitas')->toArray();

        $dataProperti = [
            ['nama' => 'Villa Bukit Indah',    'tipe' => 'villa',      'kota' => 'Bandung',     'harga' => 1500000, 'tamu' => 8,  'ruang' => 4],
            ['nama' => 'Apartemen Sudirman',   'tipe' => 'apartemen',  'kota' => 'Jakarta',     'harga' => 2000000, 'tamu' => 4,  'ruang' => 2],
            ['nama' => 'Rumah Tropik Malioboro','tipe' => 'rumah',      'kota' => 'Yogyakarta',  'harga' => 800000,  'tamu' => 6,  'ruang' => 3],
            ['nama' => 'Guesthouse Sanur',     'tipe' => 'guesthouse', 'kota' => 'Bali',        'harga' => 500000,  'tamu' => 2,  'ruang' => 1],
            ['nama' => 'Kost Premium Gubeng',  'tipe' => 'kost',       'kota' => 'Surabaya',    'harga' => 350000,  'tamu' => 1,  'ruang' => 1],
        ];

        foreach ($dataProperti as $i => $item) {
            $owner = $owners->get($i % $owners->count());

            $properti = Properti::create([
                'id_user'           => $owner->id_user,
                'nama_properti'     => $item['nama'],
                'tipe_properti'     => $item['tipe'],
                'deskripsi'         => 'Properti nyaman dan strategis di ' . $item['kota'] . '. Cocok untuk liburan keluarga atau pasangan.',
                'alamat'            => 'Jl. Contoh No. ' . ($i + 1) . ', ' . $item['kota'],
                'kota'              => $item['kota'],
                'provinsi'          => 'Jawa Barat',
                'latitude'          => -6.9 + ($i * 0.1),
                'longitude'         => 107.6 + ($i * 0.1),
                'harga_per_malam'   => $item['harga'],
                'max_tamu'          => $item['tamu'],
                'jumlah_ruang'      => $item['ruang'],
                'status'            => 'active',
                'verified_status'   => $i === 0 ? 'pending' : 'verified',
            ]);

            // Gambar
            foreach (range(1, 3) as $j) {
                Gambar::create([
                    'id_properti' => $properti->id_properti,
                    'url_gambar'  => 'https://picsum.photos/seed/prop' . $properti->id_properti . '-' . $j . '/800/600',
                    'is_primary'  => $j === 1,
                    'urutan'      => $j - 1,
                ]);
            }

            // Fasilitas (attach random 3-6)
            $selected = array_rand(array_flip($fasilitasIds), min(5, count($fasilitasIds)));
            $properti->fasilitas()->sync((array) $selected);
        }
    }
}
