<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'nama'     => 'Admin NginUp',
                'username' => 'admin',
                'email'    => 'admin@nginup.com',
                'password' => Hash::make('password'),
                'no_hp'    => '081234567890',
                'role'     => 'admin',
                'status'   => 'active',
            ],
            [
                'nama'     => 'Budi Santoso',
                'username' => 'budi',
                'email'    => 'budi@example.com',
                'password' => Hash::make('password'),
                'no_hp'    => fake()->phoneNumber(),
                'role'     => 'owner',
                'status'   => 'active',
            ],
            [
                'nama'     => 'Siti Rahmawati',
                'username' => 'siti',
                'email'    => 'siti@example.com',
                'password' => Hash::make('password'),
                'no_hp'    => fake()->phoneNumber(),
                'role'     => 'owner',
                'status'   => 'active',
            ],
            [
                'nama'     => 'Ahmad Hidayat',
                'username' => 'ahmad',
                'email'    => 'ahmad@example.com',
                'password' => Hash::make('password'),
                'no_hp'    => fake()->phoneNumber(),
                'role'     => 'owner',
                'status'   => 'active',
            ],
            [
                'nama'     => 'Dewi Lestari',
                'username' => 'dewi',
                'email'    => 'dewi@example.com',
                'password' => Hash::make('password'),
                'no_hp'    => fake()->phoneNumber(),
                'role'     => 'traveler',
                'status'   => 'active',
            ],
            [
                'nama'     => 'Rudi Hermawan',
                'username' => 'rudi',
                'email'    => 'rudi@example.com',
                'password' => Hash::make('password'),
                'no_hp'    => fake()->phoneNumber(),
                'role'     => 'traveler',
                'status'   => 'active',
            ],
            [
                'nama'     => 'Ani Wulandari',
                'username' => 'ani',
                'email'    => 'ani@example.com',
                'password' => Hash::make('password'),
                'no_hp'    => fake()->phoneNumber(),
                'role'     => 'traveler',
                'status'   => 'active',
            ],
            [
                'nama'     => 'Traveler NginUp',
                'username' => 'traveler',
                'email'    => 'traveler@nginup.com',
                'password' => Hash::make('password'),
                'no_hp'    => fake()->phoneNumber(),
                'role'     => 'traveler',
                'status'   => 'active',
            ],
            [
                'nama'     => 'Owner NginUp',
                'username' => 'owner1',
                'email'    => 'owner1@nginup.com',
                'password' => Hash::make('password'),
                'no_hp'    => fake()->phoneNumber(),
                'role'     => 'owner',
                'status'   => 'active',
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['username' => $user['username']],
                $user
            );
        }
    }
}
