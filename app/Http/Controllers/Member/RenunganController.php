<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Renungan;
use Illuminate\Http\Request;

class RenunganController extends Controller
{
    /** Halaman tulis + riwayat renungan milik sendiri. */
    public function index(Request $request)
    {
        $renungans = $request->user()->renungans()
            ->with('approver')
            ->orderByDesc('written_on')
            ->orderByDesc('id')
            ->paginate(10);

        return view('member.renungan', compact('renungans'));
    }

    /** Renungan anggota lain yang sudah di-acc admin dan masih berlaku. */
    public function komunitas(Request $request)
    {
        $renungans = Renungan::visible()
            ->where('user_id', '!=', $request->user()->id)
            ->with('user')
            ->orderByDesc('written_on')
            ->orderByDesc('approved_at')
            ->paginate(10);

        return view('member.renungan_komunitas', compact('renungans'));
    }

    public function store(Request $request)
    {
        // Tanggal renungan mengikuti tanggal menulis, tidak diisi anggota.
        $request->user()->renungans()->create($this->validated($request) + [
            'status'     => 'pending',
            'written_on' => now()->toDateString(),
        ]);

        return redirect()->route('member.renungan')->with('success', 'Renungan tersimpan dan menunggu persetujuan admin.');
    }

    public function update(Request $request, Renungan $renungan)
    {
        $this->authorizeOwner($renungan);

        $renungan->fill($this->validated($request));

        // Setiap kali peserta mengedit, renungan kembali ke antrean approval
        // supaya yang tampil ke anggota lain selalu versi yang sudah di-acc.
        $renungan->forceFill([
            'last_edited_at' => now(),
            'status'         => 'pending',
            'approved_at'    => null,
            'approved_by'    => null,
            'rejected_at'    => null,
            'reject_reason'  => null,
            'expires_at'     => null,
            'display_days'   => null,
            'hidden_at'      => null,
            'hidden_by'      => null,
        ])->save();

        return redirect()->route('member.renungan')->with('success', 'Renungan diperbarui dan menunggu persetujuan admin lagi.');
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

    private function authorizeOwner(Renungan $renungan): void
    {
        abort_unless($renungan->user_id === auth()->id(), 403);
    }
}
