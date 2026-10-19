<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberSeeder extends Seeder
{
    /**
     * Akun anggota contoh untuk development. TIDAK dibuat di production.
     */
    public function run(): void
    {
        if (! app()->environment('local')) {
            return;
        }

        $member = User::firstOrNew(['email' => 'anggota@gereja.com']);
        $member->forceFill([
            'name'     => 'Anggota Legio',
            'password' => Hash::make('123'),
            'role'     => User::ROLE_MEMBER,
        ])->save();
    }
}
