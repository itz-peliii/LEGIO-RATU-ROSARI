<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    /** Jadwal: hanya kegiatan yang belum berlangsung. */
    public function index(Request $request)
    {
        $kegiatans = Kegiatan::where('held_at', '>=', now())->orderBy('held_at')->get();
        $joinedIds = $request->user()->kegiatans()->pluck('kegiatans.id')->all();

        return view('member.jadwal', compact('kegiatans', 'joinedIds'));
    }

    public function join(Request $request, Kegiatan $kegiatan)
    {
        if ($kegiatan->isPast()) {
            return back()->with('error', 'Kegiatan ini sudah berlangsung, tidak bisa di-join.');
        }

        // syncWithoutDetaching = aman jika tombol ditekan dua kali (tidak duplikat)
        $kegiatan->members()->syncWithoutDetaching([$request->user()->id]);

        return back()->with('success', 'Kamu berhasil join "' . $kegiatan->title . '".');
    }

    public function leave(Request $request, Kegiatan $kegiatan)
    {
        if ($kegiatan->isPast()) {
            return back()->with('error', 'Kegiatan ini sudah berlangsung, tidak bisa dibatalkan.');
        }

        $kegiatan->members()->detach($request->user()->id);

        return back()->with('success', 'Kamu membatalkan kehadiran di "' . $kegiatan->title . '".');
    }
}
