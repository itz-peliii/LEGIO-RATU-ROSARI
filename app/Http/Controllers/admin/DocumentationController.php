<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2MB
        ]);

        $path = $request->file('image')->store('documentations', 'public');

        Documentation::create([
            'title' => $request->title,
            'image_path' => $path,
        ]);

        return redirect()->back()->with('success', 'Foto berhasil diunggah!');
    }

    public function destroy(Documentation $documentation)
    {
        // Hapus file dari disk storage
        if (Storage::disk('public')->exists($documentation->image_path)) {
            Storage::disk('public')->delete($documentation->image_path);
        }

        // Hapus record di DB
        $documentation->delete();

        return redirect()->back()->with('success', 'Foto berhasil dihapus!');
    }
}