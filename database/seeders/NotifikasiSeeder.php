<?php

namespace Database\Seeders;

use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotifikasiSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', '!=', 'admin')->get();

        foreach ($users as $user) {
            Notifikasi::create([
                'id_user' => $user->id_user,
                'title'   => 'Selamat datang di NginUp!',
                'pesan'   => 'Hai ' . $user->nama . ', selamat bergabung di platform NginUp.',
                'type'    => 'system',
                'is_read' => false,
            ]);
        }
    }
}
