<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $joinedIds = $request->user()->kegiatans()->pluck('kegiatans.id')->all();

        $rows = Kegiatan::orderByDesc('held_at')->get()->map(function (Kegiatan $k) use ($joinedIds) {
            return [
                'kegiatan' => $k,
                'status'   => $k->attendanceStatus(in_array($k->id, $joinedIds)),
            ];
        });

        $summary = [
            'hadir'       => $rows->where('status', 'hadir')->count(),
            'tidak_hadir' => $rows->where('status', 'tidak_hadir')->count(),
        ];

        return view('member.absensi', compact('rows', 'summary'));
    }
}
