@extends('layouts.app')
@section('title', 'Anggota')
@section('subtitle', 'Data keanggotaan komunitas')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
    <form method="GET" style="max-width:280px; width:100%;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama anggota..."
               style="width:100%; padding:10px 16px; border-radius:999px; border:2px solid #e0eff0;">
    </form>
    <a href="{{ route('members.create') }}" class="lm-btn lm-btn-primary">+ Tambah anggota</a>
</div>

<div class="lm-card">
    <table class="lm-table">
        <thead>
            <tr><th>Nama</th><th>No. HP</th><th>Bergabung</th><th>Status</th><th>Kehadiran</th><th></th></tr>
        </thead>
        <tbody>
        @foreach ($members as $m)
            <tr>
                <td>{{ $m->name }}</td>
                <td>{{ $m->phone ?? '-' }}</td>
                <td>{{ $m->join_date ? \Carbon\Carbon::parse($m->join_date)->format('d M Y') : '-' }}</td>
                <td><span class="lm-badge badge-{{ $m->status }}">{{ $m->status === 'aktif' ? 'Aktif' : 'Tidak aktif' }}</span></td>
                <td>{{ $m->attendancePercentage() }}%</td>
                <td style="white-space:nowrap;">
                    <a href="{{ route('members.edit', $m) }}" class="lm-btn lm-btn-ghost" style="padding:6px 14px;">Ubah</a>
                    <form action="{{ route('members.destroy', $m) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus anggota ini?')">
                        @csrf @method('DELETE')
                        <button class="lm-btn lm-btn-danger" style="padding:6px 14px;">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div style="margin-top:12px;">{{ $members->links() }}</div>
</div>
@endsection
