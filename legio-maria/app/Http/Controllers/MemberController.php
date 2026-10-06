<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $members = Member::when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create', ['member' => new Member()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        Member::create($data);

        return redirect()->route('members.index')->with('success', 'Anggota baru berhasil ditambahkan.');
    }

    public function edit(Member $member)
    {
        return view('members.create', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $data = $this->validated($request);
        $member->update($data);

        return redirect()->route('members.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()->route('members.index')->with('success', 'Anggota berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'gender' => 'required|in:L,P',
            'join_date' => 'nullable|date',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);
    }
}
