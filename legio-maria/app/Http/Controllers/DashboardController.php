<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Meeting;
use App\Models\VisitSchedule;
use App\Models\CashTransaction;
use App\Models\Announcement;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMembers = Member::where('status', 'aktif')->count();

        $lastMeeting = Meeting::latest('meeting_date')->first();
        $lastMeetingAttendancePct = 0;
        if ($lastMeeting) {
            $total = $lastMeeting->attendances()->count();
            $hadir = $lastMeeting->attendances()->where('status', 'hadir')->count();
            $lastMeetingAttendancePct = $total ? round(($hadir / $total) * 100, 1) : 0;
        }

        $saldoMasuk = CashTransaction::where('type', 'masuk')->sum('amount');
        $saldoKeluar = CashTransaction::where('type', 'keluar')->sum('amount');
        $saldo = $saldoMasuk - $saldoKeluar;

        $upcomingVisits = VisitSchedule::with(['visitRequest', 'assignedMember'])
            ->where('visit_date', '>=', Carbon::today())
            ->where('status', 'terjadwal')
            ->orderBy('visit_date')
            ->take(5)
            ->get();

        $announcements = Announcement::latest('event_date')->take(3)->get();

        return view('dashboard', compact(
            'totalMembers', 'lastMeeting', 'lastMeetingAttendancePct',
            'saldo', 'upcomingVisits', 'announcements'
        ));
    }
}
