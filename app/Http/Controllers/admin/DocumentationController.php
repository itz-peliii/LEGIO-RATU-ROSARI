<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2MB
        ]);

        $created = DB::transaction(function () use ($request) {
            // Dicek di dalam transaksi supaya dua upload bersamaan tidak lolos jadi 11 foto.
            if (Documentation::lockForUpdate()->count() >= Documentation::MAX) {
                return false;
            }

            $path = $request->file('image')->store('documentations', 'public');

            Documentation::create([
                'title'      => $request->title,
                'image_path' => $path,
                'sort_order' => (int) Documentation::max('sort_order') + 1, // masuk di urutan paling akhir
            ]);

            return true;
        });

        if (! $created) {
            return $this->backToTab()->with(
                'doc_error',
                'Batas ' . Documentation::MAX . ' foto sudah tercapai. Hapus salah satu foto terlebih dahulu untuk mengunggah yang baru.'
            );
        }

        return $this->backToTab()->with('success', 'Foto berhasil diunggah!');
    }

    public function destroy(Documentation $documentation)
    {
        // Hapus file dari disk storage
        if (Storage::disk('public')->exists($documentation->image_path)) {
            Storage::disk('public')->delete($documentation->image_path);
        }

        // Hapus record di DB
        $documentation->delete();

        // Rapikan urutan jadi 1..N tanpa celah
        Documentation::ordered()->get()->each(function ($doc, $i) {
            if ((int) $doc->sort_order !== $i + 1) {
                $doc->update(['sort_order' => $i + 1]);
            }
        });

        return $this->backToTab()->with('success', 'Foto berhasil dihapus!');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|distinct',
        ]);

        $order    = array_map('intval', $request->input('order'));
        $existing = Documentation::pluck('id')->map(fn ($v) => (int) $v)->all();

        // Daftar yang dikirim harus persis sama dengan foto yang ada.
        if (count($order) !== count($existing) || array_diff($order, $existing)) {
            return $this->backToTab()->with('doc_error', 'Daftar foto sudah berubah. Muat ulang halaman lalu atur urutan lagi.');
        }

        DB::transaction(function () use ($order) {
            foreach ($order as $i => $id) {
                Documentation::whereKey($id)->update(['sort_order' => $i + 1]);
            }
        });

        return $this->backToTab()->with('success', 'Urutan foto berhasil disimpan!');
    }

    /** Kembali ke halaman admin dengan tab Dokumentasi terbuka. */
    private function backToTab(): RedirectResponse
    {
        return redirect()->back()->with('active_tab', 'dok');
    }
}