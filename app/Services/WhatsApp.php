<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsApp
{
    public static function send(string $phone, string $message): bool
    {
        $res = Http::withHeaders(['Authorization' => config('services.fonnte.token')])
            ->asForm()
            ->post('https://api.fonnte.com/send', [
                'target'      => $phone,   // 08xx otomatis jadi 628xx
                'message'     => $message,
                'countryCode' => '62',
            ]);

        if (! $res->successful() || ! $res->json('status')) {
            Log::warning('Gagal kirim WA', ['phone' => $phone, 'body' => $res->body()]);
            return false;
        }

        return true;
    }
}