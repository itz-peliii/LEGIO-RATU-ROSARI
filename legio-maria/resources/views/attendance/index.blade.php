@extends('layouts.app')
@section('title', 'Presensi')
@section('subtitle', 'Daftar pertemuan mingguan & rekap kehadiran')

@section('content')
<div style="margin-bottom:16px;">
    <a href="{{ route('attendance.create') }}" class="lm-btn lm-btn-primary">+ Buat pertemuan baru</a>
</div>

<div class="lm-card">
    <table class="lm-table">
        <thead><tr><th>Tanggal</th><th>Topik</th><th>Jumlah hadir</th><th></th></tr></thead>
        <tbody>
        @foreach ($meetings as $m)
            @php
                $total = $m->attendances()->count();
                $hadir = $m->attendances()->where('status','hadir')->count();
            @endphp
            <tr>
                <td>{{ \Carbon\Carbon::parse($m->meeting_date)->format('d M Y') }}</td>
                <td>{{ $m->topic ?? '-' }}</td>
                <td>{{ $hadir }} / {{ $total }}</td>
                <td><a href="{{ route('attendance.mark', $m) }}" class="lm-btn lm-btn-ghost" style="padding:6px 14px;">Isi / lihat presensi</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div style="margin-top:12px;">{{ $meetings->links() }}</div>
</div>
@endsection
