<?php

namespace App\Console\Commands;

use App\Services\WhatsApp;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class KirimPengingatKegiatan extends Command
{
    protected $signature = 'kegiatan:pengingat';
    protected $description = 'Kirim pengingat WA H-x ke anggota yang ikut kegiatan';

    public function handle(): void
    {
        $rows = DB::table('kegiatan_user as ku')
            ->join('kegiatans as k', 'k.id', '=', 'ku.kegiatan_id')
            ->join('users as u', 'u.id', '=', 'ku.user_id')
            ->whereNull('ku.reminded_at')
            ->whereNotNull('u.phone')
            ->where('k.held_at', '>', now())
            // sudah masuk H-x: tanggal kegiatan - remind_days <= hari ini
            ->whereRaw('DATE_SUB(DATE(k.held_at), INTERVAL ku.remind_days DAY) <= CURDATE()')
            ->select('ku.kegiatan_id', 'ku.user_id', 'u.name', 'u.phone',
                     'k.title', 'k.location', 'k.held_at')
            ->get();

        foreach ($rows as $r) {
            $waktu = Carbon::parse($r->held_at)->locale('id');
            $sisa  = now()->startOfDay()->diffInDays($waktu->copy()->startOfDay());

            $pesan = "Salam Maria, {$r->name} 🙏\n\n"
                . "Pengingat: *{$r->title}* "
                . ($sisa <= 1 ? 'besok' : "{$sisa} hari lagi") . ".\n\n"
                . "📅 {$waktu->translatedFormat('l, d F Y')}\n"
                . "🕐 Pukul {$waktu->format('H.i')}\n"
                . "📍 {$r->location}\n\n"
                . "Sampai jumpa di sana!";

            if (WhatsApp::send($r->phone, $pesan)) {
                DB::table('kegiatan_user')
                    ->where('kegiatan_id', $r->kegiatan_id)
                    ->where('user_id', $r->user_id)
                    ->update(['reminded_at' => now()]);
            }

            sleep(2); // jeda agar nomor WA tidak dianggap spam
        }

        $this->info("Terkirim/diproses: {$rows->count()}");
    }
}