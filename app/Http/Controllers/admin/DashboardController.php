<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Documentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua setting dalam format key-value
        $settings = Setting::pluck('value', 'key')->all();
        
        // Ambil seluruh dokumentasi terurut
        $documentations = Documentation::orderBy('sort_order', 'asc')->latest()->get();

        return view('admin.dashboard', compact('settings', 'documentations'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'hero_title'       => 'nullable|string',
            'hero_subtitle'    => 'nullable|string',
            'visi_misi'        => 'nullable|string',
            'doc_description'  => 'nullable|string',
        ]);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->back()->with('success', 'Konten website berhasil diperbarui!');
    }
}