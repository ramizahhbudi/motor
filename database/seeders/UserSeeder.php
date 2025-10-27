<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
// use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Membuat Admin
        User::create([
            'name' => 'Admin Bengkel',
            'email' => 'admin@bengkel.com',
            'password' => 'password', // Ganti password sesuai kebutuhan
            'role' => 'admin',
            'pin' => '123456', // Contoh PIN untuk admin
            'phone' => '081234567890',
        ]);

        // Membuat Mekanik
        User::create([
            'name' => 'Mekanik A',
            'email' => 'mekanik_a@bengkel.com',
            'password' => 'password', // Ganti password sesuai kebutuhan
            'role' => 'mekanik',
            'pin' => '654321', // Contoh PIN untuk mekanik
            'phone' => '081234567891',
        ]);

        // Membuat User Biasa
        User::create([
            'name' => 'Ijah',
            'email' => 'ijah@gmail.com',
            'password' => 'password', // Ganti password sesuai kebutuhan
            'role' => 'user',
            'pin' => '111222', // Contoh PIN untuk user biasa
            'phone' => '081234567892',
        ]);
    }
}
