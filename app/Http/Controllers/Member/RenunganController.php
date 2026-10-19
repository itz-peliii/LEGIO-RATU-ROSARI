<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RenunganController extends Controller
{
    public function index(Request $request)
    {
        $renungans = $request->user()->renungans()
            ->orderByDesc('written_on')
            ->orderByDesc('id')
            ->limit(20)
            ->get();

        return view('member.renungan', compact('renungans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'written_on' => ['required', 'date'],
            'content'    => ['required', 'string', 'max:5000'],
        ]);

        $request->user()->renungans()->create($data);

        return redirect()->route('member.renungan')->with('success', 'Renungan harian berhasil disimpan.');
    }
}
