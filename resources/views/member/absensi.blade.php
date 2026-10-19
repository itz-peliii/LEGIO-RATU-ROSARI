@extends('layouts.member')

@section('title', 'Absensi')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900">Absensi Kegiatan Legio</h1>
    <p class="text-sm text-slate-500 mt-1">Riwayat kehadiranmu di setiap kegiatan.</p>
</div>

<div class="grid grid-cols-2 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <p class="text-xs uppercase tracking-wide text-slate-500">Hadir</p>
        <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $summary['hadir'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <p class="text-xs uppercase tracking-wide text-slate-500">Tidak hadir</p>
        <p class="text-3xl font-bold text-red-600 mt-1">{{ $summary['tidak_hadir'] }}</p>
    </div>
</div>

@php
    $badge = [
        'hadir'       => ['Hadir',                   'bg-emerald-100 text-emerald-800'],
        'tidak_hadir' => ['Tidak hadir',             'bg-red-100 text-red-800'],
        'terdaftar'   => ['Terdaftar (belum berlangsung)', 'bg-indigo-100 text-indigo-800'],
        'belum'       => ['Belum join',              'bg-slate-100 text-slate-600'],
    ];
@endphp

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    @forelse ($rows as $row)
        @php($k = $row['kegiatan'])
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-5 py-4 {{ ! $loop->last ? 'border-b border-slate-100' : '' }}">
            <div>
                <p class="font-semibold text-slate-900">{{ $k->title }}</p>
                <p class="text-sm text-slate-500">
                    {{ $k->held_at->locale('id')->translatedFormat('l, d F Y · H.i') }} · {{ $k->location }}
                </p>
            </div>
            <span class="self-start sm:self-auto shrink-0 px-3 py-1 rounded-full text-xs font-semibold {{ $badge[$row['status']][1] }}">
                {{ $badge[$row['status']][0] }}
            </span>
        </div>
    @empty
        <p class="p-8 text-center text-slate-500 text-sm">Belum ada kegiatan.</p>
    @endforelse
</div>
@endsection
