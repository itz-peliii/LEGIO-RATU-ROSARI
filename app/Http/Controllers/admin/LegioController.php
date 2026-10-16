<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Legio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LegioController extends Controller
{
    private function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validateWithBag('legio', $this->rules());

        $legio = new Legio([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'sort_order'  => (Legio::max('sort_order') ?? 0) + 1,
        ]);

        if ($request->hasFile('photo')) {
            $legio->photo_path = $request->file('photo')->store('legio', 'public');
        }

        $legio->save();

        return redirect()->back()->with('success', 'Legio berhasil ditambahkan!');
    }

    public function update(Request $request, Legio $legio)
    {
        $data = $request->validateWithBag('legio', $this->rules());

        $legio->name        = $data['name'];
        $legio->description = $data['description'] ?? null;

        if ($request->hasFile('photo')) {
            $this->deletePhoto($legio);
            $legio->photo_path = $request->file('photo')->store('legio', 'public');
        } elseif ($request->boolean('remove_photo')) {
            $this->deletePhoto($legio);
            $legio->photo_path = null;
        }

        $legio->save();

        return redirect()->back()->with('success', 'Legio berhasil diperbarui!');
    }

    public function destroy(Legio $legio)
    {
        $this->deletePhoto($legio);
        $legio->delete();

        return redirect()->back()->with('success', 'Legio berhasil dihapus!');
    }

    private function deletePhoto(Legio $legio): void
    {
        if ($legio->photo_path) {
            Storage::disk('public')->delete($legio->photo_path);
        }
    }
}