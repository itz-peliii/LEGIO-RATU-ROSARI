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
        // firstOrNew + forceFill: aman dijalankan berulang kali, dan bisa mengisi 'role'
        // (yang sengaja tidak mass-assignable).
        $admin = User::firstOrNew(['email' => 'admin@gereja.com']);
        $admin->forceFill([
            'name'     => 'Admin Gereja',
            'password' => Hash::make('123'),
            'role'     => User::ROLE_ADMIN,
        ])->save();
    }
}
