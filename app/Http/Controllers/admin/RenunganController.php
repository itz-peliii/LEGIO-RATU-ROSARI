<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Renungan;
use Illuminate\Http\Request;

class RenunganController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'pending');

        $query = Renungan::with(['user', 'approver', 'hider']);

        match ($tab) {
            'tampil'   => $query->visible(),
            'hidden'   => $query->whereNotNull('hidden_at'),
            'expired'  => $query->where('status', 'approved')
                                ->whereNotNull('expires_at')
                                ->where('expires_at', '<=', now()),
            'rejected' => $query->where('status', 'rejected'),
            'semua'    => $query,
            default    => $query->where('status', 'pending'),
        };

        $renungans = $query->latest('created_at')->paginate(15)->withQueryString();

        $counts = [
            'pending' => Renungan::where('status', 'pending')->count(),
            'tampil'  => Renungan::visible()->count(),
        ];

        return view('admin.renungan.index', compact('renungans', 'tab', 'counts'));
    }

    public function approve(Request $request, Renungan $renungan)
    {
        $data = $request->validate([
            // kosong = tampil sampai admin memutuskan untuk menyembunyikan
            'display_days' => ['nullable', 'integer', 'min:1', 'max:365'],
        ]);

        $days = $data['display_days'] ?? null;

        $renungan->update([
            'status'        => 'approved',
            'approved_at'   => now(),
            'approved_by'   => auth()->id(),
            'rejected_at'   => null,
            'reject_reason' => null,
            'hidden_at'     => null,
            'hidden_by'     => null,
            'display_days'  => $days,
            'expires_at'    => $days ? now()->addDays($days) : null,
        ]);

        return back()->with('success', 'Renungan disetujui dan sudah tampil untuk anggota lain.');
    }

    public function reject(Request $request, Renungan $renungan)
    {
        $data = $request->validate([
            'reject_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $renungan->update([
            'status'        => 'rejected',
            'rejected_at'   => now(),
            'reject_reason' => $data['reject_reason'] ?? null,
            'approved_at'   => null,
            'approved_by'   => null,
            'expires_at'    => null,
            'display_days'  => null,
        ]);

        return back()->with('success', 'Renungan ditolak.');
    }

    public function hide(Renungan $renungan)
    {
        $renungan->update([
            'hidden_at' => now(),
            'hidden_by' => auth()->id(),
        ]);

        return back()->with('success', 'Renungan disembunyikan dari halaman anggota.');
    }

    public function unhide(Renungan $renungan)
    {
        $renungan->update([
            'hidden_at' => null,
            'hidden_by' => null,
        ]);

        return back()->with('success', 'Renungan ditampilkan kembali.');
    }

    /** Perpanjang masa tampil, atau jadikan tanpa batas waktu. */
    public function extend(Request $request, Renungan $renungan)
    {
        $data = $request->validate([
            'display_days' => ['nullable', 'integer', 'min:1', 'max:365'],
        ]);

        $days = $data['display_days'] ?? null;

        $renungan->update([
            'display_days' => $days,
            'expires_at'   => $days ? now()->addDays($days) : null,
            'hidden_at'    => null,
            'hidden_by'    => null,
        ]);

        return back()->with('success', $days
            ? "Masa tampil diperpanjang {$days} hari."
            : 'Renungan tampil tanpa batas waktu.');
    }
}
