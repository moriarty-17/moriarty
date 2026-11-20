<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Menggunakan updateOrCreate agar tidak error duplikat jika dijalankan 2x
        Pengguna::updateOrCreate(
            ['email' => 'sirmoriarty@merchant.gg'], // Cari berdasarkan email ini
            [
                'username'  => 'qwdkn213123',
                'password'  => Hash::make('hohoho'), // Password otomatis di-hash
                'role'      => 'admin', // Role dikunci sebagai admin
            ]
        );
    }
}