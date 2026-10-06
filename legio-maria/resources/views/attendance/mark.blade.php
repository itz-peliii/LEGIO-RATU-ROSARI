@extends('layouts.app')
@section('title', 'Presensi ' . \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y'))

@section('content')
<div class="lm-card">
    <form method="POST" action="{{ route('attendance.saveMark', $meeting) }}">
        @csrf
        <table class="lm-table">
            <thead><tr><th>Nama anggota</th><th>Status kehadiran</th></tr></thead>
            <tbody>
            @foreach ($members as $mem)
                <tr>
                    <td>{{ $mem->name }}</td>
                    <td>
                        <select name="status[{{ $mem->id }}]">
                            <option value="hadir" {{ ($existing[$mem->id] ?? '') === 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="izin" {{ ($existing[$mem->id] ?? '') === 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="absen" {{ ($existing[$mem->id] ?? '') === 'absen' ? 'selected' : '' }}>Absen</option>
                        </select>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <button class="lm-btn lm-btn-primary" type="submit" style="margin-top:16px;">Simpan presensi</button>
    </form>
</div>
@endsection
