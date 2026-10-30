<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Reading;
use Illuminate\Http\Request;

class BacaanController extends Controller
{
    /** Viewer bacaan untuk anggota. ?week=N untuk pindah minggu. */
    public function index(Request $request)
    {
        $reading = Reading::with('overrides')
            ->where('is_active', true)
            ->latest()
            ->first();

        if (! $reading) {
            return view('member.bacaan', ['reading' => null]);
        }

        $current = $reading->currentWeekIndex();
        $max     = $reading->lastBrowsableWeek();

        // minggu yang diminta, dibatasi 0..max
        $week = (int) $request->query('week', $current);
        $week = max(0, min($week, $max));

        $info    = $reading->weekInfo($week);
        $hasPrev = $week > 0;
        $hasNext = $week < $max;

        return view('member.bacaan', compact(
            'reading', 'info', 'week', 'current', 'max', 'hasPrev', 'hasNext'
        ));
    }
}
