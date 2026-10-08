<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menggunakan updateOrCreate agar seeder aman dijalankan berulang kali tanpa error duplicate email
        User::updateOrCreate(
            ['email' => 'admin@gereja.com'],
            [
                'name'     => 'Admin Gereja',
                'password' => Hash::make('123'),
            ]
        );
    }
}