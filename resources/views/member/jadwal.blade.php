@extends('layouts.member')

@section('title', 'Jadwal Kegiatan')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900">Jadwal Kegiatan</h1>
    <p class="text-sm text-slate-500 mt-1">Kegiatan Legio yang akan datang. Tekan <strong>Join</strong> untuk menyatakan kehadiranmu.</p>
</div>

@if ($kegiatans->isEmpty())
    <div class="bg-white rounded-xl border border-slate-200 p-10 text-center text-slate-500 text-sm">
        Belum ada kegiatan yang dijadwalkan.
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ($kegiatans as $k)
            @php($joined = in_array($k->id, $joinedIds))
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 flex flex-col">
                <h2 class="text-lg font-semibold text-slate-900">{{ $k->title }}</h2>

                <dl class="mt-3 space-y-1.5 text-sm text-slate-600">
                    <div class="flex gap-2">
                        <dt class="font-medium text-slate-500 w-16 shrink-0">Tanggal</dt>
                        <dd>{{ $k->held_at->locale('id')->translatedFormat('l, d F Y · H.i') }}</dd>
                    </div>
                    <div class="flex gap-2">
                        <dt class="font-medium text-slate-500 w-16 shrink-0">Lokasi</dt>
                        <dd>{{ $k->location }}</dd>
                    </div>
                </dl>

                @if ($k->description)
                    <p class="mt-4 text-sm text-slate-600 leading-relaxed flex-grow">{!! nl2br(e($k->description)) !!}</p>
                @else
                    <div class="flex-grow"></div>
                @endif

                <div class="mt-6">
                    @if ($joined)
                        <div class="flex items-center justify-between gap-3">
                            <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Sudah join
                            </span>
                            <form method="POST" action="{{ route('member.jadwal.leave', $k) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-slate-500 hover:text-red-600 underline transition">Batalkan</button>
                            </form>
                        </div>
                    @else
                        <form method="POST" action="{{ route('member.jadwal.join', $k) }}">
                            @csrf
                            <button type="submit" class="w-full bg-indigo-600 text-white text-sm font-semibold py-2.5 rounded-lg hover:bg-indigo-700 transition">
                                Join
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
