<?php

use App\Models\User;
use App\Services\WhatsApp;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('kegiatan:pengingat')->dailyAt('07:00')->timezone('Asia/Jakarta');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('wa:tes {email}', function (string $email) {
    $user = User::where('email', $email)->first();

    if (! $user) {
        $this->error("User dengan email {$email} tidak ditemukan.");
        return;
    }

    if (blank($user->phone)) {
        $this->error("{$user->name} belum mengisi nomor WhatsApp.");
        return;
    }

    $ok = WhatsApp::send(
        $user->phone,
        "Salam Maria, {$user->name} 🙏\n\nIni pesan tes dari sistem Legio Paroki Santo Petrus Tuban."
    );

    $ok
        ? $this->info("✔ Terkirim ke {$user->name} ({$user->phone})")
        : $this->error('✘ Gagal. Cek storage/logs/laravel.log');
})->purpose('Kirim WA tes ke satu member');