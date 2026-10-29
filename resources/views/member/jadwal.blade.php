@extends('layouts.member')

@section('title', 'Jadwal Kegiatan')

@section('content')
@php
    /*
     | Filter & urutan dihitung di sini dari $kegiatans yang sudah dikirim
     | KegiatanController, jadi controller tidak perlu diubah.
     | Catatan: ini mengandaikan $kegiatans berupa Collection (bukan paginate).
     */
    $now = now();
    $all = collect($kegiatans);

    $cari   = trim((string) request('cari'));
    $lokasi = trim((string) request('lokasi'));
    $status = request('status', 'semua');
    $urut   = request('urut', 'terdekat');

    $daftarLokasi = $all->pluck('location')->filter()->unique()->sort()->values();

    $items = $all;

    if ($cari !== '') {
        $items = $items->filter(function ($k) use ($cari) {
            $teks = mb_strtolower($k->title.' '.$k->location.' '.$k->description);
            return str_contains($teks, mb_strtolower($cari));
        });
    }

    if ($lokasi !== '') {
        $items = $items->where('location', $lokasi);
    }

    if ($status === 'sudah') {
        $items = $items->filter(fn ($k) => in_array($k->id, $joinedIds));
    } elseif ($status === 'belum') {
        $items = $items->reject(fn ($k) => in_array($k->id, $joinedIds));
    }

    $items = match ($urut) {
        'terjauh' => $items->sortByDesc(fn ($k) => $k->held_at),
        'judul'   => $items->sortBy(fn ($k) => mb_strtolower($k->title)),
        'lokasi'  => $items->sortBy(fn ($k) => mb_strtolower($k->location).$k->held_at),
        default   => $items->sortBy(fn ($k) => $k->held_at),
    };

    $items = $items->values();

    $adaFilter = $cari !== '' || $lokasi !== '' || $status !== 'semua' || $urut !== 'terdekat';

    $kelasInput = 'w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm text-[#16233f] bg-white focus:outline-none focus:ring-2 focus:ring-[#e6cf9b] focus:border-[#b98a3e] transition';
    $kelasLabel = 'block text-[11px] font-semibold uppercase tracking-[.14em] text-slate-500 mb-1.5';
@endphp

<div class="mb-7">
    <h1 class="serif text-3xl font-bold text-[#16233f]">Jadwal Kegiatan</h1>
    <p class="text-sm text-slate-500 mt-1">
        Kegiatan Legio yang akan datang. Tekan <strong class="text-[#16233f]">Ikut</strong> untuk menyatakan kehadiranmu.
    </p>
</div>

{{-- ===== FILTER & URUTAN ===== --}}
<form method="GET" action="{{ route('member.jadwal') }}"
      class="bg-white rounded-2xl border border-slate-200 shadow-[0_18px_40px_-32px_rgba(22,35,63,.5)] p-5 mb-6">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="lg:col-span-1">
            <label for="cari" class="{{ $kelasLabel }}">Cari</label>
            <input id="cari" type="search" name="cari" value="{{ $cari }}" placeholder="Nama kegiatan atau tempat"
                   class="{{ $kelasInput }}">
        </div>

        <div>
            <label for="lokasi" class="{{ $kelasLabel }}">Tempat</label>
            <div class="relative">
                <select id="lokasi" name="lokasi" onchange="this.form.submit()" class="appearance-none pr-11 cursor-pointer {{ $kelasInput }}">
                    <option value="">Semua tempat</option>
                    @foreach ($daftarLokasi as $l)
                        <option value="{{ $l }}" @selected($lokasi === $l)>{{ $l }}</option>
                    @endforeach
                </select>
                <svg class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#b98a3e]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
            </div>
        </div>

        <div>
            <label for="status" class="{{ $kelasLabel }}">Keikutsertaan</label>
            <div class="relative">
                <select id="status" name="status" onchange="this.form.submit()" class="appearance-none pr-11 cursor-pointer {{ $kelasInput }}">
                    <option value="semua" @selected($status === 'semua')>Semua kegiatan</option>
                    <option value="sudah" @selected($status === 'sudah')>Sudah saya ikuti</option>
                    <option value="belum" @selected($status === 'belum')>Belum saya ikuti</option>
                </select>
                <svg class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#b98a3e]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
            </div>
        </div>

        <div>
            <label for="urut" class="{{ $kelasLabel }}">Urutkan</label>
            <div class="relative">
                <select id="urut" name="urut" onchange="this.form.submit()" class="appearance-none pr-11 cursor-pointer {{ $kelasInput }}">
                    <option value="terdekat" @selected($urut === 'terdekat')>Tanggal terdekat</option>
                    <option value="terjauh" @selected($urut === 'terjauh')>Tanggal terjauh</option>
                    <option value="judul" @selected($urut === 'judul')>Nama kegiatan (A–Z)</option>
                    <option value="lokasi" @selected($urut === 'lokasi')>Tempat (A–Z)</option>
                </select>
                <svg class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#b98a3e]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-3 mt-4 pt-4 border-t border-slate-100">
        <p class="text-sm text-slate-500">
            Menampilkan <span class="font-semibold text-[#16233f]">{{ $items->count() }}</span> dari {{ $all->count() }} kegiatan.
        </p>

        <div class="flex items-center gap-4">
            @if ($adaFilter)
                <a href="{{ route('member.jadwal') }}" class="text-sm font-semibold text-slate-500 hover:text-[#b98a3e] transition">
                    Atur ulang
                </a>
            @endif
            <button type="submit" class="px-5 py-2 rounded-full text-sm font-semibold text-white"
                    style="background: linear-gradient(135deg, #c79646, #b98a3e 55%, #a87c33); box-shadow: 0 10px 22px -12px rgba(185,138,62,.9);">
                Terapkan
            </button>
        </div>
    </div>
</form>

{{-- ===== DAFTAR KEGIATAN ===== --}}
@if ($all->isEmpty())
    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
        <p class="serif text-xl font-bold text-[#16233f]">Belum ada kegiatan yang dijadwalkan</p>
        <p class="text-sm text-slate-500 mt-1">Jadwal akan muncul di sini begitu pengurus menambahkannya.</p>
    </div>
@elseif ($items->isEmpty())
    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
        <p class="serif text-xl font-bold text-[#16233f]">Tidak ada kegiatan yang cocok</p>
        <p class="text-sm text-slate-500 mt-1">Coba ubah kata kunci atau longgarkan filternya.</p>
        <a href="{{ route('member.jadwal') }}" class="inline-block mt-4 text-sm font-semibold text-[#b98a3e] hover:text-[#8f6a2c] transition">
            Atur ulang filter
        </a>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach ($items as $k)
            @php
                $joined  = in_array($k->id, $joinedIds);
                $lewat   = $k->held_at->isPast();
                $hariIni = $k->held_at->isToday();
                $dekat   = ! $lewat && ! $hariIni && $k->held_at->diffInDays($now) <= 7;
            @endphp

            <article class="bg-white rounded-2xl border border-slate-200 shadow-[0_18px_40px_-32px_rgba(22,35,63,.5)] overflow-hidden flex flex-col {{ $lewat ? 'opacity-75' : '' }}">

                {{-- Kepala kartu: tanggal + penanda --}}
                <div class="flex items-start gap-4 p-5 pb-4">
                    <div class="flex-none w-16 rounded-xl bg-[#16233f] text-white text-center py-2.5 leading-none">
                        <span class="block text-[10px] font-bold uppercase tracking-[.14em] text-[#e6cf9b]">
                            {{ $k->held_at->locale('id')->translatedFormat('M') }}
                        </span>
                        <span class="serif block text-3xl font-bold mt-1">{{ $k->held_at->format('d') }}</span>
                        <span class="block text-[10px] text-slate-300 mt-1.5">{{ $k->held_at->format('Y') }}</span>
                    </div>

                    <div class="min-w-0 flex-1">
                        @if ($hariIni)
                            <span class="inline-block mb-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-[.12em] bg-[#b98a3e] text-white">Hari ini</span>
                        @elseif ($lewat)
                            <span class="inline-block mb-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-[.12em] bg-slate-200 text-slate-600">Sudah berlangsung</span>
                        @elseif ($dekat)
                            <span class="inline-block mb-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-[.12em] bg-amber-100 text-amber-800">Minggu ini</span>
                        @endif

                        <h2 class="serif text-xl font-bold text-[#16233f] leading-snug">{{ $k->title }}</h2>

                        <p class="text-xs text-slate-500 mt-1.5">
                            {{ $k->held_at->locale('id')->translatedFormat('l') }} &middot; pukul {{ $k->held_at->format('H.i') }}
                        </p>
                    </div>
                </div>

                {{-- Lokasi --}}
                <div class="px-5 pb-4">
                    <p class="flex items-start gap-2 text-sm text-slate-600">
                        <svg class="w-4 h-4 flex-none mt-0.5 text-[#b98a3e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $k->location }}</span>
                    </p>
                </div>

                {{-- Keterangan --}}
                @if ($k->description)
                    <div class="px-5 pb-5 flex-grow">
                        <p class="text-sm text-slate-600 leading-relaxed line-clamp-3">{{ $k->description }}</p>
                    </div>
                @else
                    <div class="flex-grow"></div>
                @endif

                {{-- Aksi --}}
                <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/60">
                    @if ($joined)
                        <div class="flex items-center justify-between gap-3">
                            <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Sudah ikut
                            </span>

                            @unless ($lewat)
                                <form method="POST" action="{{ route('member.jadwal.leave', $k) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-semibold text-slate-500 hover:text-red-600 transition">Batalkan</button>
                                </form>
                            @endunless
                        </div>
                    @elseif ($lewat)
                        <span class="text-sm text-slate-400">Tidak diikuti</span>
                    @else
                        <form method="POST" action="{{ route('member.jadwal.join', $k) }}">
                            @csrf
                            <button type="submit" class="w-full py-2.5 rounded-full text-sm font-semibold text-white transition hover:brightness-110"
                                    style="background: linear-gradient(135deg, #c79646, #b98a3e 55%, #a87c33); box-shadow: 0 10px 22px -12px rgba(185,138,62,.9);">
                                Ikut
                            </button>
                        </form>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
@endif
@endsection
