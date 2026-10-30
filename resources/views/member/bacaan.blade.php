@extends('layouts.member')

@section('title', 'Bacaan Mingguan')

@section('content')

@if(! $reading)
    {{-- Belum ada bacaan aktif --}}
    <div class="text-center py-20 px-6 bg-white border-2 border-dashed border-slate-200 rounded-2xl">
        <div class="mx-auto w-14 h-14 rounded-full grid place-items-center mb-4" style="background:#fbf6ea; color:#b98a3e;">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.5C10.8 5.7 9.2 5.3 7.5 5.3S4.2 5.7 3 6.5v13c1.2-.8 2.8-1.2 4.5-1.2s3.3.4 4.5 1.2m0-13c1.2-.8 2.8-1.2 4.5-1.2s3.3.4 4.5 1.2v13c-1.2-.8-2.8-1.2-4.5-1.2s-3.3.4-4.5 1.2m0-13v13"/></svg>
        </div>
        <h2 class="serif text-2xl font-bold text-[#16233f] mb-1">Belum ada bacaan</h2>
        <p class="text-slate-500 text-sm">Bacaan renungan akan muncul di sini setelah pengurus mengunggahnya.</p>
    </div>
@else
    {{-- Header --}}
    <header class="mb-6">
        <span class="inline-flex items-center gap-2 text-xs font-extrabold tracking-[0.2em] uppercase mb-2" style="color:#b98a3e;">
            <span class="w-6 h-px" style="background:#b98a3e;"></span> Renungan Legio
        </span>
        <h1 class="serif text-3xl md:text-4xl font-bold text-[#16233f] leading-tight">{{ $reading->title }}</h1>
    </header>

    {{-- Kartu minggu + navigasi --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 md:p-6 mb-5 flex flex-col md:flex-row md:items-center md:justify-between gap-5"
         style="box-shadow:0 18px 40px -30px rgba(22,35,63,.5);">
        <div class="flex items-center gap-4">
            <div class="flex-none w-14 h-14 rounded-xl grid place-items-center text-white serif font-bold text-2xl"
                 style="background:linear-gradient(135deg,#16233f,#22345c);">
                {{ $info['number'] }}
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <p class="serif text-xl font-bold text-[#16233f] leading-none">Minggu ke-{{ $info['number'] }}</p>
                    @if($info['is_current'])
                        <span class="text-[11px] font-extrabold uppercase tracking-wide text-white px-2 py-0.5 rounded-full" style="background:#b98a3e;">Minggu ini</span>
                    @endif
                </div>
                <p class="text-sm text-slate-500 mt-1">
                    {{ $info['start']->translatedFormat('d M Y') }} – {{ $info['end']->translatedFormat('d M Y') }}
                    · Halaman <b class="text-[#16233f]">{{ $info['page'] }}</b>
                </p>
                @if($info['note'])
                    <p class="text-sm mt-1" style="color:#8a5a00;">“{{ $info['note'] }}”</p>
                @endif
            </div>
        </div>

        {{-- Navigasi minggu --}}
        <div class="flex items-center gap-2 flex-wrap">
            @if($hasPrev)
                <a href="{{ route('member.bacaan', ['week' => $week - 1]) }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full text-sm font-bold text-[#16233f] bg-white border border-slate-300 hover:border-[#b98a3e] hover:text-[#b98a3e] transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5l-7 7 7 7"/></svg>
                    Minggu lalu
                </a>
            @else
                <span class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full text-sm font-bold text-slate-300 bg-white border border-slate-200 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5l-7 7 7 7"/></svg>
                    Minggu lalu
                </span>
            @endif

            @if($week !== $current)
                <a href="{{ route('member.bacaan') }}" class="inline-flex items-center px-4 py-2.5 rounded-full text-sm font-bold text-white transition hover:brightness-110"
                   style="background:linear-gradient(135deg,#c79646,#b98a3e 55%,#a87c33);">
                    Minggu ini
                </a>
            @endif

            @if($hasNext)
                <a href="{{ route('member.bacaan', ['week' => $week + 1]) }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full text-sm font-bold text-[#16233f] bg-white border border-slate-300 hover:border-[#b98a3e] hover:text-[#b98a3e] transition">
                    Minggu depan
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full text-sm font-bold text-slate-300 bg-white border border-slate-200 cursor-not-allowed">
                    Minggu depan
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>
    </div>

    {{-- Viewer PDF --}}
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm" style="box-shadow:0 18px 40px -30px rgba(22,35,63,.5);">
        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between gap-3 flex-wrap">
            <p class="text-xs text-slate-500">
                Terbuka di halaman <b class="text-[#16233f]">{{ $info['page'] }}</b>. Kamu bisa menggulir untuk membaca halaman lain.
            </p>
            <div class="flex items-center gap-3">
                <a href="{{ $reading->fileUrl() }}#page={{ $info['page'] }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#16233f] hover:text-[#b98a3e] transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 3h6v6M10 14L21 3M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                    Buka tab baru
                </a>
                <a href="{{ $reading->fileUrl() }}" download class="inline-flex items-center gap-1.5 text-xs font-bold text-[#16233f] hover:text-[#b98a3e] transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v12m0 0l-4-4m4 4l4-4M5 20h14"/></svg>
                    Unduh
                </a>
            </div>
        </div>

        {{-- key={{ $info['page'] }} memaksa iframe reload ke halaman baru saat pindah minggu --}}
        <iframe
            src="{{ $reading->fileUrl() }}#page={{ $info['page'] }}&view=FitH"
            class="w-full"
            style="height:78vh; min-height:32rem; border:0; display:block; background:#f1f4f9;"
            title="PDF bacaan — {{ $reading->title }}, halaman {{ $info['page'] }}"></iframe>
    </div>

    <p class="text-center text-xs text-slate-400 mt-4">
        Jika PDF tidak terbuka di halaman yang tepat, gunakan tombol <b>Buka tab baru</b> atau <b>Unduh</b>.
    </p>
@endif

@endsection
