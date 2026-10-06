<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Member;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Menampilkan daftar pertemuan
    public function index()
    {
        $meetings = Meeting::orderByDesc('meeting_date')->paginate(10);

        return view('attendance.index', compact('meetings'));
    }

    // Membuat pertemuan baru sekaligus menyiapkan form presensi untuk semua anggota aktif
    public function create()
    {
        return view('attendance.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'meeting_date' => 'required|date',
            'topic' => 'nullable|string|max:255',
        ]);

        $meeting = Meeting::create($request->only('meeting_date', 'topic'));

        return redirect()->route('attendance.mark', $meeting)->with('success', 'Pertemuan dibuat. Silakan isi presensi.');
    }

    // Form untuk mencatat kehadiran tiap anggota pada satu pertemuan
    public function mark(Meeting $meeting)
    {
        $members = Member::where('status', 'aktif')->orderBy('name')->get();
        $existing = $meeting->attendances()->pluck('status', 'member_id');

        return view('attendance.mark', compact('meeting', 'members', 'existing'));
    }

    public function saveMark(Request $request, Meeting $meeting)
    {
        $data = $request->validate([
            'status' => 'required|array',
            'status.*' => 'in:hadir,izin,absen',
        ]);

        foreach ($data['status'] as $memberId => $status) {
            Attendance::updateOrCreate(
                ['meeting_id' => $meeting->id, 'member_id' => $memberId],
                ['status' => $status]
            );
        }

        return redirect()->route('attendance.index')->with('success', 'Presensi berhasil disimpan.');
    }
}
