<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Renungan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RenunganController extends Controller
{
    /** Halaman tulis + riwayat renungan milik sendiri (draft selalu tampil di atas). */
    public function index(Request $request)
    {
        $user = $request->user();

        $months = $this->monthsOf($user->renungans()->where('status', '!=', 'draft'));
        $bulan  = $this->resolveMonth($request->query('bulan'), $months);

        $query = $user->renungans()->with('approver');

        // Draft selalu tampil, sisanya mengikuti bulan yang dipilih.
        $query->where(function ($q) use ($bulan) {
            $q->where('status', 'draft');
            if ($bulan) {
                [$start, $end] = $this->range($bulan);
                $q->orWhereBetween('written_on', [$start, $end]);
            } else {
                $q->orWhere('status', '!=', 'draft');
            }
        });

        $renungans = $query
            ->orderByRaw("CASE WHEN status = 'draft' THEN 0 ELSE 1 END")
            ->orderByDesc('written_on')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('member.renungan', compact('renungans', 'months', 'bulan'));
    }

    /** Renungan anggota lain yang sudah di-acc admin dan masih berlaku. */
    public function komunitas(Request $request)
    {
        $base = fn () => Renungan::visible()->where('user_id', '!=', $request->user()->id);

        $months = $this->monthsOf($base());
        $bulan  = $this->resolveMonth($request->query('bulan'), $months);

        $query = $base()->with('user');

        if ($bulan) {
            [$start, $end] = $this->range($bulan);
            $query->whereBetween('written_on', [$start, $end]);
        }

        $renungans = $query
            ->orderByDesc('written_on')
            ->orderByDesc('approved_at')
            ->paginate(10)
            ->withQueryString();

        return view('member.renungan_komunitas', compact('renungans', 'months', 'bulan'));
    }

    public function store(Request $request)
    {
        $status = $this->statusFromAction($request);

        // Tanggal renungan mengikuti tanggal menulis, tidak diisi anggota.
        $request->user()->renungans()->create($this->validated($request) + [
            'status'     => $status,
            'written_on' => now()->toDateString(),
        ]);

        return redirect()->route('member.renungan')->with(
            'success',
            $status === 'draft'
                ? 'Draft tersimpan. Kamu bisa melanjutkannya kapan saja.'
                : 'Renungan terkirim dan menunggu persetujuan admin.'
        );
    }

    public function update(Request $request, Renungan $renungan)
    {
        $this->authorizeOwner($renungan);

        // Renungan yang sudah disetujui dibekukan: tidak bisa diedit anggota.
        if ($renungan->status === 'approved') {
            return redirect()->route('member.renungan')
                ->with('error', 'Renungan yang sudah disetujui admin tidak bisa diedit lagi.');
        }

        $status = $this->statusFromAction($request);

        $renungan->fill($this->validated($request));

        // Edit apa pun (kecuali approved, yang sudah diblokir di atas) mengembalikan
        // renungan ke antrean approval, atau tetap draft kalau belum dikirim.
        $renungan->forceFill([
            'last_edited_at' => now(),
            'status'         => $status,
            'approved_at'    => null,
            'approved_by'    => null,
            'rejected_at'    => null,
            'reject_reason'  => null,
            'expires_at'     => null,
            'display_days'   => null,
            'hidden_at'      => null,
            'hidden_by'      => null,
        ])->save();

        return redirect()->route('member.renungan')->with(
            'success',
            $status === 'draft' ? 'Draft diperbarui.' : 'Renungan terkirim dan menunggu persetujuan admin.'
        );
    }

    public function destroy(Renungan $renungan)
    {
        $this->authorizeOwner($renungan);
        $renungan->delete();

        return redirect()->route('member.renungan')->with('success', 'Renungan dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:5000'],
        ]);
    }

    /** Tombol "Simpan draft" mengirim aksi=draft, tombol "Kirim" mengirim aksi=kirim. */
    private function statusFromAction(Request $request): string
    {
        return $request->input('aksi') === 'draft' ? 'draft' : 'pending';
    }

    private function authorizeOwner(Renungan $renungan): void
    {
        abort_unless($renungan->user_id === auth()->id(), 403);
    }

    /** Daftar bulan (Y-m) yang punya renungan, terbaru dulu. */
    private function monthsOf($query): Collection
    {
        return $query->orderByDesc('written_on')
            ->pluck('written_on')
            ->map(fn ($d) => Carbon::parse($d)->format('Y-m'))
            ->unique()
            ->values();
    }

    /** null = semua bulan. Default (tanpa parameter) = bulan terbaru yang punya data. */
    private function resolveMonth(?string $requested, Collection $months): ?string
    {
        if ($requested === 'all') {
            return null;
        }

        if ($requested && $months->contains($requested)) {
            return $requested;
        }

        return $months->first();
    }

    private function range(string $bulan): array
    {
        $start = Carbon::createFromFormat('Y-m-d', $bulan . '-01')->startOfMonth();

        return [$start->toDateString(), $start->copy()->endOfMonth()->toDateString()];
    }
}