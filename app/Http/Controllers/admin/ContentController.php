<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function edit()
    {
        $settings = Setting::pluck('value', 'key')->all();
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'about_us' => 'nullable|string',
            'visi'     => 'nullable|string',
            'misi'     => 'nullable|string',
            'phone'    => 'nullable|string',
            'email'    => 'nullable|email',
            'address'  => 'nullable|string',
        ]);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->back()->with('success', 'Informasi website berhasil diperbarui!');
    }
}