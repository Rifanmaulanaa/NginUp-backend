<?php

namespace Database\Seeders;

use App\Models\Fasilitas;
use Illuminate\Database\Seeder;

class FasilitasSeeder extends Seeder
{
    public function run(): void
    {
        $fasilitas = [
            ['nama_fasilitas' => 'WiFi Gratis',     'ikon_fasilitas' => 'wifi'],
            ['nama_fasilitas' => 'AC',              'ikon_fasilitas' => 'snowflake'],
            ['nama_fasilitas' => 'Kolam Renang',    'ikon_fasilitas' => 'person-swimming'],
            ['nama_fasilitas' => 'Parkir Gratis',   'ikon_fasilitas' => 'car'],
            ['nama_fasilitas' => 'Dapur',           'ikon_fasilitas' => 'kitchen-set'],
            ['nama_fasilitas' => 'Mesin Cuci',      'ikon_fasilitas' => 'soap'],
            ['nama_fasilitas' => 'TV Kabel',        'ikon_fasilitas' => 'tv'],
            ['nama_fasilitas' => 'AC Taman',        'ikon_fasilitas' => 'tree'],
            ['nama_fasilitas' => 'Gym',             'ikon_fasilitas' => 'dumbbell'],
            ['nama_fasilitas' => 'Sarapan Gratis',  'ikon_fasilitas' => 'coffee'],
        ];

        foreach ($fasilitas as $item) {
            Fasilitas::updateOrCreate(
                ['nama_fasilitas' => $item['nama_fasilitas']],
                ['ikon_fasilitas' => $item['ikon_fasilitas']]
            );
        }
    }
}
