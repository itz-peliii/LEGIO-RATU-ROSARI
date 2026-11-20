<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Reading;
use App\Models\Renungan;
use Illuminate\Http\Request;

class RenunganController extends Controller
{
    /** Halaman tulis + riwayat renungan milik sendiri. */
    public function index(Request $request)
    {
        // Ambil bacaan aktif minggu ini (sesuaikan jika nama model bacaanmu berbeda dari Reading)
        $reading = class_exists(Reading::class) ? Reading::latest()->first() : null;
        $currentWeek = ($reading && method_exists($reading, 'weekInfo'))
            ? $reading->weekInfo($reading->currentWeekIndex())
            : null;

        $query = $request->user()->renungans()
            ->with('approver')
            ->orderByDesc('written_on')
            ->orderByDesc('id');

        // Filter 1: Cari judul renungan atau nama penulis
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('name', 'like', "%{$s}%");
            });
        }

        // Filter 2: Bulan (format YYYY-MM)
        if ($request->filled('month')) {
            [$year, $month] = explode('-', $request->month);
            $query->whereYear('written_on', $year)
                  ->whereMonth('written_on', $month);
        }

        // Filter 3: Minggu ke-
        if ($request->filled('week')) {
            $query->where('week_number', $request->week);
        }

        $renungans = $query->paginate(10)->withQueryString();

        return view('member.renungan', compact('renungans', 'reading', 'currentWeek'));
    }

    /** Renungan anggota lain yang sudah di-acc admin dan masih berlaku. */
    public function komunitas(Request $request)
    {
        $query = Renungan::visible()
            ->where('user_id', '!=', $request->user()->id)
            ->with('user')
            ->orderByDesc('written_on')
            ->orderByDesc('approved_at');

        // Filter opsional di halaman komunitas
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('name', 'like', "%{$s}%");
            });
        }

        if ($request->filled('month')) {
            [$year, $month] = explode('-', $request->month);
            $query->whereYear('written_on', $year)
                  ->whereMonth('written_on', $month);
        }

        if ($request->filled('week')) {
            $query->where('week_number', $request->week);
        }

        $renungans = $query->paginate(10)->withQueryString();

        return view('member.renungan_komunitas', compact('renungans'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $isDraft = ($data['action'] ?? 'submit') === 'draft';

        $request->user()->renungans()->create([
            'name'        => $data['name'],
            'title'       => $data['title'],
            'content'     => $data['content'],
            'week_number' => $data['week_number'] ?? null,
            'written_on'  => now()->toDateString(),
            'status'      => $isDraft ? 'draft' : 'pending',
        ]);

        return redirect()->route('member.renungan')->with('success', $isDraft
            ? 'Renungan berhasil disimpan ke Draft.'
            : 'Renungan dikirim dan menunggu persetujuan admin.');
    }

    public function update(Request $request, Renungan $renungan)
    {
        $this->authorizeOwner($renungan);

        // Kunci edit jika sudah disubmit (pending / approved)
        if (! $renungan->canBeEditedByMember()) {
            return redirect()->route('member.renungan')
                ->with('error', 'Renungan yang sudah dikirim atau sedang tayang tidak dapat diedit.');
        }

        $data = $this->validated($request);
        $isDraft = ($data['action'] ?? 'submit') === 'draft';

        $renungan->fill([
            'name'    => $data['name'],
            'title'   => $data['title'],
            'content' => $data['content'],
        ]);

        $renungan->forceFill([
            'last_edited_at' => now(),
            'status'         => $isDraft ? 'draft' : 'pending',
            // Jika dikirim ulang setelah ditolak, bersihkan catatan penolakan lama
            'rejected_at'    => $isDraft ? $renungan->rejected_at : null,
            'reject_reason'  => $isDraft ? $renungan->reject_reason : null,
            'approved_at'    => null,
            'approved_by'    => null,
            'expires_at'     => null,
            'display_days'   => null,
            'hidden_at'      => null,
            'hidden_by'      => null,
        ])->save();

        return redirect()->route('member.renungan')->with('success', $isDraft
            ? 'Draft renungan diperbarui.'
            : 'Renungan diperbarui dan dikirim untuk persetujuan admin.');
    }

    public function destroy(Renungan $renungan)
    {
        $this->authorizeOwner($renungan);

        // Mencegah member menghapus renungan yang sudah tayang di komunitas
        if ($renungan->status === 'approved') {
            return redirect()->route('member.renungan')
                ->with('error', 'Renungan yang sudah tayang tidak dapat dihapus sendiri.');
        }

        $renungan->delete();

        return redirect()->route('member.renungan')->with('success', 'Renungan dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'title'       => ['required', 'string', 'max:255'],
            'content'     => ['required', 'string', 'max:5000'],
            'week_number' => ['nullable', 'integer', 'min:1'],
            'action'      => ['nullable', 'in:draft,submit'],
        ]);
    }

    private function authorizeOwner(Renungan $renungan): void
    {
        abort_unless($renungan->user_id === auth()->id(), 403);
    }
}