@extends('layouts.app')
@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan kegiatan komunitas minggu ini')

@section('content')
<div class="lm-hero">
    <h2>Selamat datang kembali 🙏</h2>
    <p>Pantau keanggotaan, presensi, kunjungan pelayanan, dan kas komunitas dalam satu tampilan.</p>
</div>

<div class="lm-stats">
    <div class="lm-stat-card">
        <div class="icon-bubble bg-sea">👥</div>
        <div class="value">{{ $totalMembers }}</div>
        <div class="label">Anggota aktif</div>
    </div>
    <div class="lm-stat-card">
        <div class="icon-bubble bg-turquoise">✅</div>
        <div class="value">{{ $lastMeetingAttendancePct }}%</div>
        <div class="label">Kehadiran pertemuan terakhir</div>
    </div>
    <div class="lm-stat-card">
        <div class="icon-bubble bg-deep">💰</div>
        <div class="value">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
        <div class="label">Saldo kas saat ini</div>
    </div>
    <div class="lm-stat-card">
        <div class="icon-bubble bg-blush">🏠</div>
        <div class="value">{{ $upcomingVisits->count() }}</div>
        <div class="label">Kunjungan mendatang</div>
    </div>
</div>

<div class="lm-card">
    <h3>Jadwal kunjungan mendatang</h3>
    @forelse ($upcomingVisits as $v)
        <p style="margin:8px 0; font-size:14px;">
            <strong>{{ $v->visit_date->format('d M Y') }}</strong> —
            {{ $v->visitRequest->beneficiary_name }} ({{ $v->visitRequest->category }}) ·
            ditugaskan ke <strong>{{ $v->assignedMember->name }}</strong>
        </p>
    @empty
        <p style="color:#6b8791; font-size:14px;">Belum ada kunjungan yang dijadwalkan.</p>
    @endforelse
</div>

<div class="lm-card">
    <h3>Pengumuman terbaru</h3>
    @forelse ($announcements as $a)
        <p style="margin:8px 0; font-size:14px;"><strong>{{ $a->title }}</strong> — {{ \Illuminate\Support\Str::limit($a->content, 90) }}</p>
    @empty
        <p style="color:#6b8791; font-size:14px;">Belum ada pengumuman.</p>
    @endforelse
</div>
@endsection
