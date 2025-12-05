<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin LOFO
        User::create([
            'name' => 'Admin LoFo',
            'email' => 'admin@lofo.com',
            'password' => Hash::make('password'), // password default
            'role' => 'admin',
            'phone_number' => '081234567890',
        ]);

        // 2. Akun Security (Satpam)
        User::create([
            'name' => 'Pak Satpam',
            'email' => 'security@lofo.com',
            'password' => Hash::make('password'),
            'role' => 'security',
            'phone_number' => '089876543210',
        ]);

        // 3. Dummy Mahasiswa (Untuk testing)
        User::create([
            'name' => 'Budi Mahasiswa',
            'email' => 'budi@student.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'student_id' => '12345678',
            'phone_number' => '08111222333',
        ]);
    }
}