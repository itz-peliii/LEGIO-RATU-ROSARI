<?php

namespace App\Http\Controllers;

use App\Models\VisitRequest;
use App\Models\VisitSchedule;
use App\Models\Member;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function index()
    {
        $requests = VisitRequest::with('schedule.assignedMember')->latest()->paginate(10);

        return view('visits.index', compact('requests'));
    }

    public function create()
    {
        $members = Member::where('status', 'aktif')->orderBy('name')->get();

        return view('visits.create', compact('members'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'requested_by' => 'nullable|exists:members,id',
            'beneficiary_name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'category' => 'required|in:sakit,berduka',
            'notes' => 'nullable|string',
        ]);

        VisitRequest::create($data);

        return redirect()->route('visits.index')->with('success', 'Usulan kunjungan berhasil diajukan.');
    }

    // Menjadwalkan tim/anggota yang bertugas untuk sebuah usulan kunjungan
    public function schedule(VisitRequest $visitRequest)
    {
        $members = Member::where('status', 'aktif')->orderBy('name')->get();

        return view('visits.schedule', compact('visitRequest', 'members'));
    }

    public function storeSchedule(Request $request, VisitRequest $visitRequest)
    {
        $data = $request->validate([
            'assigned_member_id' => 'required|exists:members,id',
            'visit_date' => 'required|date',
        ]);

        VisitSchedule::create([
            'visit_request_id' => $visitRequest->id,
            'assigned_member_id' => $data['assigned_member_id'],
            'visit_date' => $data['visit_date'],
            'status' => 'terjadwal',
        ]);

        $visitRequest->update(['status' => 'dijadwalkan']);

        // TODO: kirim notifikasi WhatsApp H-1 di sini, lihat catatan di README
        // (gunakan job/scheduler Laravel + provider WA seperti Fonnte/Twilio)

        return redirect()->route('visits.index')->with('success', 'Jadwal kunjungan berhasil dibuat.');
    }
}
