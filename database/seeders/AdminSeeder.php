<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Jalankan seeder ini untuk membuat akun admin default.
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'username' => 'admin123',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'), // Ganti password sesuai kebutuhan
            'role' => 1, // Sesuaikan dengan sistem role Anda
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}


