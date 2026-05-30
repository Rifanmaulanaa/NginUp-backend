<?php

namespace Database\Seeders;

use App\Models\Aturan;
use Illuminate\Database\Seeder;

class AturanSeeder extends Seeder
{
    public function run(): void
    {
        $aturan = [
            ['text_aturan' => 'Dilarang merokok di dalam ruangan'],
            ['text_aturan' => 'Dilarang membawa hewan peliharaan'],
            ['text_aturan' => 'Check-in setelah pukul 14:00'],
            ['text_aturan' => 'Check-out sebelum pukul 12:00'],
            ['text_aturan' => 'Tidak boleh mengadakan pesta / event'],
            ['text_aturan' => 'Jaga kebersihan selama menginap'],
            ['text_aturan' => 'Dilarang memindahkan furnitur'],
            ['text_aturan' => 'Tidah boleh membawa tamu luar tanpa izin'],
        ];

        foreach ($aturan as $item) {
            Aturan::create($item);
        }
    }
}
