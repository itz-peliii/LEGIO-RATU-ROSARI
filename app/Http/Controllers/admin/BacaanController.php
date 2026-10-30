<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reading;
use App\Models\ReadingOverride;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BacaanController extends Controller
{
    /** Halaman kelola Bacaan Mingguan di admin. */
    public function index()
    {
        $reading = Reading::with('overrides')
            ->where('is_active', true)
            ->latest()
            ->first();

        $weeks = [];
        if ($reading) {
            $current = $reading->currentWeekIndex();
            $from = max(0, $current - 2);   // tampilkan 2 minggu lalu ...
            $to   = $current + 11;          // ... sampai 11 minggu ke depan
            for ($w = $from; $w <= $to; $w++) {
                $weeks[] = $reading->weekInfo($w);
            }
        }

        return view('admin.bacaan.index', compact('reading', 'weeks'));
    }

    /** Unggah PDF baru (jadi bacaan aktif). */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'pdf'            => 'required|file|mimes:pdf|max:20480', // 20 MB
            'total_pages'    => 'nullable|integer|min:1',
            'start_date'     => 'required|date',
            'start_page'     => 'required|integer|min:1',
            'pages_per_week' => 'required|integer|min:1',
        ]);

        $path = $request->file('pdf')->store('bacaan', 'public');

        // hanya satu bacaan aktif
        Reading::where('is_active', true)->update(['is_active' => false]);

        Reading::create([
            'title'          => $data['title'],
            'file_path'      => $path,
            'total_pages'    => $data['total_pages'] ?? null,
            'start_date'     => $data['start_date'],
            'start_page'     => $data['start_page'],
            'pages_per_week' => $data['pages_per_week'],
            'is_active'      => true,
            'uploaded_by'    => auth()->id(),
        ]);

        return redirect()->route('admin.bacaan.index')
            ->with('success', 'PDF bacaan berhasil diunggah.');
    }

    /** Simpan pengaturan jadwal + (opsional) ganti file PDF. */
    public function update(Request $request, Reading $reading)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'total_pages'    => 'nullable|integer|min:1',
            'start_date'     => 'required|date',
            'start_page'     => 'required|integer|min:1',
            'pages_per_week' => 'required|integer|min:1',
            'pdf'            => 'nullable|file|mimes:pdf|max:20480',
        ]);

        if ($request->hasFile('pdf')) {
            Storage::disk('public')->delete($reading->file_path);
            $reading->file_path = $request->file('pdf')->store('bacaan', 'public');
        }

        $reading->fill([
            'title'          => $data['title'],
            'total_pages'    => $data['total_pages'] ?? null,
            'start_date'     => $data['start_date'],
            'start_page'     => $data['start_page'],
            'pages_per_week' => $data['pages_per_week'],
            'is_active'      => true,
        ])->save();

        return back()->with('success', 'Pengaturan bacaan berhasil disimpan.');
    }

    /** Hapus PDF bacaan beserta filenya. */
    public function destroy(Reading $reading)
    {
        Storage::disk('public')->delete($reading->file_path);
        $reading->delete(); // override ikut terhapus (cascade)

        return redirect()->route('admin.bacaan.index')
            ->with('success', 'PDF bacaan dihapus.');
    }

    /** Atur / timpa halaman untuk satu minggu. */
    public function storeOverride(Request $request, Reading $reading)
    {
        $rulePage = 'required|integer|min:1';
        if ($reading->total_pages) {
            $rulePage .= '|max:' . $reading->total_pages;
        }

        $data = $request->validate([
            'week_start' => 'required|date',
            'page'       => $rulePage,
            'note'       => 'nullable|string|max:255',
        ]);

        $reading->overrides()->updateOrCreate(
            ['week_start' => $data['week_start']],
            ['page' => $data['page'], 'note' => $data['note'] ?? null]
        );

        return back()->with('success', 'Halaman minggu tersebut berhasil diatur manual.');
    }

    /** Hapus override → minggu itu kembali ke jadwal otomatis. */
    public function destroyOverride(ReadingOverride $override)
    {
        $override->delete();

        return back()->with('success', 'Pengaturan manual dihapus; minggu itu kembali otomatis.');
    }
}
