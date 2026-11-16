@extends('layouts.member')

@section('title', 'Renungan Anggota')

@section('content')
@php
    $labelBulan = fn ($ym) => \Carbon\Carbon::createFromFormat('Y-m', $ym)->locale('id')->translatedFormat('F Y');
@endphp

<div class="mb-7 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="serif text-3xl font-bold text-[#16233f]">Renungan Anggota</h1>
        <p class="text-sm text-slate-500 mt-1">
            Renungan dari anggota lain yang sudah disetujui oleh Admin.
            @if ($bulan)
                Menampilkan <span class="font-semibold text-[#16233f]">{{ $labelBulan($bulan) }}</span>.
            @else
                Menampilkan semua bulan.
            @endif
        </p>
    </div>

    @if ($months->isNotEmpty())
        <form method="GET" action="{{ route('member.renungan.komunitas') }}" class="relative">
            <label for="bulan" class="sr-only">Bulan</label>
            <select id="bulan" name="bulan" onchange="this.form.submit()"
                    class="appearance-none pl-4 pr-11 py-2.5 border border-slate-300 rounded-lg text-sm font-semibold text-[#16233f] bg-white cursor-pointer hover:border-[#b98a3e] focus:outline-none focus:ring-2 focus:ring-[#e6cf9b] transition">
                @foreach ($months as $ym)
                    <option value="{{ $ym }}" @selected($bulan === $ym)>{{ $labelBulan($ym) }}</option>
                @endforeach
                <option value="all" @selected($bulan === null)>Semua bulan</option>
            </select>
            <svg class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-[#b98a3e]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
        </form>
    @endif
</div>

@forelse ($renungans as $r)
    <article class="bg-white rounded-2xl border border-slate-200 shadow-[0_18px_40px_-32px_rgba(22,35,63,.5)] overflow-hidden mb-5">

        <header class="flex flex-wrap items-center justify-between gap-2 px-6 py-4 border-b border-slate-100">
            <div class="flex items-center gap-2.5 min-w-0">
                <span class="w-1.5 h-6 rounded-full bg-[#b98a3e] flex-none" aria-hidden="true"></span>
                <span class="serif text-lg font-bold text-[#16233f] [overflow-wrap:anywhere]">{{ $r->name }}</span>
            </div>
            <time class="text-xs font-semibold uppercase tracking-[.1em] text-slate-400" datetime="{{ $r->written_on->toDateString() }}">
                {{ $r->written_on->locale('id')->translatedFormat('l, d F Y') }}
            </time>
        </header>

        <div class="px-6 py-5">
            <p class="text-[15px] text-slate-700 leading-[1.85] whitespace-pre-line [overflow-wrap:anywhere]">{{ $r->content }}</p>
        </div>

        @if ($r->expires_at)
            <footer class="px-6 py-3 border-t border-slate-100 bg-slate-50/60">
                <p class="text-[11px] text-slate-400">
                    Tampil sampai {{ $r->expires_at->locale('id')->translatedFormat('d F Y') }}
                </p>
            </footer>
        @endif
    </article>
@empty
    <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center">
        <p class="serif text-xl font-bold text-[#16233f]">Belum ada renungan dari anggota lain</p>
        <p class="text-sm text-slate-500 mt-1">
            Renungan akan muncul di sini setelah ditulis anggota dan disetujui oleh Admin.
        </p>
        <a href="{{ route('member.renungan') }}" class="inline-block mt-4 text-sm font-semibold text-[#b98a3e] hover:text-[#8f6a2c] transition">
            Tulis renunganmu sendiri
        </a>
    </div>
@endforelse

@if ($renungans instanceof \Illuminate\Contracts\Pagination\Paginator)
    <div class="mt-6">{{ $renungans->links() }}</div>
@endif
@endsection