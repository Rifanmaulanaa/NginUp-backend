<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            FasilitasSeeder::class,
            AturanSeeder::class,
            UserSeeder::class,
            PropertiSeeder::class,
            PemesananSeeder::class,
            NotifikasiSeeder::class,
        ]);
    }
}
