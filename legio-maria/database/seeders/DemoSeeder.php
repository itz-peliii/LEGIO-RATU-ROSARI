<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Meeting;
use App\Models\Attendance;
use App\Models\CashTransaction;
use App\Models\Announcement;
use Illuminate\Database\Seeder;

// Jalankan dengan: php artisan db:seed --class=Database\\Seeders\\DemoSeeder
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $members = collect(['Maria Sari', 'Yohanes Budi', 'Fransiska Ayu', 'Petrus Anton', 'Elisabeth Dewi'])
            ->map(fn ($name) => Member::create([
                'name' => $name,
                'phone' => '08' . rand(1000000000, 1999999999),
                'gender' => 'P',
                'join_date' => now()->subYears(rand(1, 5)),
                'status' => 'aktif',
            ]));

        $meeting = Meeting::create([
            'meeting_date' => now()->subDays(7),
            'topic' => 'Doa Rosario & Evaluasi Pelayanan Bulanan',
        ]);

        foreach ($members as $m) {
            Attendance::create([
                'meeting_id' => $meeting->id,
                'member_id' => $m->id,
                'status' => collect(['hadir', 'hadir', 'izin', 'absen'])->random(),
            ]);
        }

        CashTransaction::create(['type' => 'masuk', 'category' => 'Iuran bulanan', 'amount' => 500000, 'transaction_date' => now()->subDays(5)]);
        CashTransaction::create(['type' => 'keluar', 'category' => 'Bantuan sosial', 'amount' => 150000, 'transaction_date' => now()->subDays(2)]);

        Announcement::create([
            'title' => 'Pertemuan mingguan pindah jam',
            'content' => 'Pertemuan minggu depan dimajukan ke pukul 18.00 di aula paroki.',
            'event_date' => now()->addDays(6),
        ]);
    }
}
