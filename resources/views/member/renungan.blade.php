@extends('layouts.member')

@section('title', 'Renungan Saya')

@section('content')
@php
    $kelasInput  = 'w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm text-[#16233f] bg-white focus:outline-none focus:ring-2 focus:ring-[#e6cf9b] focus:border-[#b98a3e] transition';
    $kelasLabel  = 'block text-[11px] font-semibold uppercase tracking-[.14em] text-slate-500 mb-1.5';
    $gayaTombol  = 'background: linear-gradient(135deg, #c79646, #b98a3e 55%, #a87c33); box-shadow: 0 10px 22px -12px rgba(185,138,62,.9);';
    $kelasTombol = 'px-7 py-2.5 rounded-full text-sm font-semibold text-white transition hover:brightness-110';

    // Kalau update gagal validasi, kartu yang sedang diedit dibuka kembali otomatis.
    $sedangEdit = old('renungan_id');
@endphp

<div class="mb-7">
    <h1 class="serif text-3xl font-bold text-[#16233f]">Renungan Saya</h1>
    <p class="text-sm text-slate-500 mt-1">
        Baca bagian bacaan minggu ini, simpan tulisanmu sebagai draft, atau kirim untuk ditampilkan di renungan komunitas setelah disetujui admin.
    </p>
</div>

{{-- ===== INFO BACAAN MINGGU INI ===== --}}
@if (!empty($reading) && !empty($currentWeek))
    <div class="bg-[#fbf6ea] border border-[#e6cf9b] rounded-2xl p-5 mb-8 flex flex-wrap items-center justify-between gap-4 shadow-[0_14px_30px_-24px_rgba(22,35,63,.35)]">
        <div>
            <span class="text-[11px] font-bold uppercase tracking-[.16em] text-[#b98a3e]">
                Bacaan Minggu Ke-{{ $currentWeek['number'] }}
            </span>
            <h2 class="serif text-xl font-bold text-[#16233f] mt-0.5">
                {{ $reading->title }} — Halaman {{ $currentWeek['page'] }}
            </h2>
            <p class="text-xs text-slate-600 mt-1">
                Periode {{ $currentWeek['start']->locale('id')->translatedFormat('d M Y') }} s/d {{ $currentWeek['end']->locale('id')->translatedFormat('d M Y') }}
            </p>
        </div>
        <a href="{{ $reading->fileUrl() }}#page={{ $currentWeek['page'] }}" target="_blank"
           class="px-5 py-2.5 rounded-full text-xs font-bold bg-[#16233f] text-white hover:bg-[#22345c] transition">
            Buka PDF Halaman {{ $currentWeek['page'] }} &rarr;
        </a>
    </div>
@endif

{{-- ===== FORM TULIS ===== --}}
<form method="POST" action="{{ route('member.renungan.store') }}"
      class="bg-white rounded-2xl border border-slate-200 shadow-[0_18px_40px_-32px_rgba(22,35,63,.5)] overflow-hidden mb-12">

    <div class="flex flex-wrap items-center justify-between gap-2 px-6 py-4 border-b border-slate-100 bg-slate-50/60">
        <h2 class="serif text-xl font-bold text-[#16233f]">Tulis Renungan</h2>
        <p class="text-xs font-semibold text-slate-500">
            {{ now()->locale('id')->translatedFormat('l, d F Y') }}
            @if(!empty($currentWeek))
                &middot; Minggu ke-{{ $currentWeek['number'] }}
            @endif
        </p>
    </div>

    <div class="px-6 py-5 space-y-4">
        @csrf
        <input type="hidden" name="week_number" value="{{ $currentWeek['number'] ?? '' }}">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="name" class="{{ $kelasLabel }}">Nama Penulis</label>
                <input id="name" type="text" name="name"
                       value="{{ $sedangEdit ? auth()->user()->name : old('name', auth()->user()->name) }}"
                       required maxlength="255" class="{{ $kelasInput }}">
                @if (! $sedangEdit && $errors->has('name'))
                    <p class="mt-1.5 text-sm text-red-600">{{ $errors->first('name') }}</p>
                @endif
            </div>

            <div>
                <label for="title" class="{{ $kelasLabel }}">Judul Renungan</label>
                <input id="title" type="text" name="title"
                       value="{{ $sedangEdit ? '' : old('title') }}"
                       required maxlength="255" placeholder="Contoh: Buah Kesabaran dalam Pelayanan"
                       class="{{ $kelasInput }}">
                @if (! $sedangEdit && $errors->has('title'))
                    <p class="mt-1.5 text-sm text-red-600">{{ $errors->first('title') }}</p>
                @endif
            </div>
        </div>

        <div x-data="{ isi: @js($sedangEdit ? '' : old('content', '')) }">
            <label for="content" class="{{ $kelasLabel }}">Renungan</label>
            <textarea id="content" name="content" rows="8" required maxlength="5000" x-model="isi"
                      placeholder="Tulis renunganmu di sini..."
                      class="{{ $kelasInput }} leading-relaxed">{{ $sedangEdit ? '' : old('content') }}</textarea>

            <div class="flex items-center justify-between mt-1.5">
                @if (! $sedangEdit && $errors->has('content'))
                    <p class="text-sm text-red-600">{{ $errors->first('content') }}</p>
                @else
                    <span></span>
                @endif
                <span class="text-[11px] text-slate-400 tabular-nums" x-text="isi.length + ' / 5000'">0 / 5000</span>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-4 pt-2 border-t border-slate-100">
            <p class="text-xs text-slate-400 leading-relaxed">
                Simpan ke <b>Draft</b> jika masih ingin diubah nanti. Setelah <b>Dikirim</b>, renungan terkunci sampai ditinjau admin.
            </p>
            <div class="flex items-center gap-3">
                <button type="submit" name="action" value="draft"
                        class="px-5 py-2.5 rounded-full text-sm font-semibold text-[#16233f] bg-slate-100 hover:bg-slate-200 transition">
                    Simpan ke Draft
                </button>
                <button type="submit" name="action" value="submit" class="{{ $kelasTombol }} flex-none" style="{{ $gayaTombol }}">
                    Kirim
                </button>
            </div>
        </div>
    </div>
</form>

{{-- ===== RIWAYAT RENUNGAN SENDIRI ===== --}}
<div class="flex items-center gap-4 mb-4">
    <h2 class="serif text-2xl font-bold text-[#16233f] flex-none">Renungan yang sudah saya tulis</h2>
    <span class="h-px flex-1 bg-gradient-to-r from-[#e6cf9b] to-transparent"></span>
</div>

{{-- ===== FILTER RIWAYAT ===== --}}
<form method="GET" action="{{ route('member.renungan') }}"
      class="bg-white p-4 rounded-2xl border border-slate-200 shadow-[0_14px_30px_-24px_rgba(22,35,63,.35)] mb-6 grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
    <div>
        <label for="filter-search" class="{{ $kelasLabel }}">Cari Judul / Nama</label>
        <input id="filter-search" type="text" name="search" value="{{ request('search') }}"
               placeholder="Kata kunci..." class="{{ $kelasInput }}">
    </div>
    <div>
        <label for="filter-month" class="{{ $kelasLabel }}">Bulan</label>
        <input id="filter-month" type="month" name="month" value="{{ request('month') }}" class="{{ $kelasInput }}">
    </div>
    <div>
        <label for="filter-week" class="{{ $kelasLabel }}">Minggu Ke-</label>
        <input id="filter-week" type="number" name="week" min="1" value="{{ request('week') }}"
               placeholder="Semua minggu" class="{{ $kelasInput }}">
    </div>
    <div class="flex items-center gap-2">
        <button type="submit"
                class="flex-1 px-5 py-2.5 rounded-lg text-xs font-bold bg-[#16233f] text-white hover:bg-[#22345c] transition">
            Filter
        </button>
        @if (request()->hasAny(['search', 'month', 'week']))
            <a href="{{ route('member.renungan') }}"
               class="px-4 py-2.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
                Reset
            </a>
        @endif
    </div>
</form>

@forelse ($renungans as $r)
    @php
        $bukaEdit = (string) $sedangEdit === (string) $r->id;
        $bisaEdit = $r->canBeEditedByMember(); // Hanya true jika 'draft' atau 'rejected'
    @endphp

    <article x-data="{ edit: {{ $bukaEdit ? 'true' : 'false' }} }"
             class="bg-white rounded-2xl border border-slate-200 shadow-[0_18px_40px_-32px_rgba(22,35,63,.5)] overflow-hidden mb-4">

        <header class="flex flex-wrap items-center justify-between gap-2 px-6 py-4 border-b border-slate-100">
            <div>
                <h3 class="serif text-lg font-bold text-[#16233f]">{{ $r->title ?? 'Tanpa Judul' }}</h3>
                <time class="text-xs font-medium text-slate-500" datetime="{{ $r->written_on->toDateString() }}">
                    {{ $r->written_on->locale('id')->translatedFormat('l, d F Y') }}
                    @if ($r->week_number)
                        &middot; Minggu ke-{{ $r->week_number }}
                    @endif
                </time>
            </div>
            <span class="text-[11px] font-bold uppercase tracking-[.1em] px-2.5 py-1 rounded-full {{ $r->statusColor() }}">
                {{ $r->statusLabel() }}
            </span>
        </header>

        {{-- ----- MODE BACA ----- --}}
        <div x-show="!edit">
            <div class="px-6 py-5">
                <p class="text-[15px] text-slate-700 leading-[1.8] whitespace-pre-line">{{ $r->content }}</p>

                @if ($r->status === 'rejected')
                    <div class="mt-4 text-xs text-red-700 bg-red-50 border-l-2 border-red-400 px-3.5 py-2.5 rounded">
                        <p class="font-semibold">Renungan ini belum disetujui admin. Kamu bisa mengedit dan mengirimkannya kembali.</p>
                        @if ($r->reject_reason)
                            <p class="mt-1"><span class="font-semibold">Catatan admin:</span> {{ $r->reject_reason }}</p>
                        @endif
                    </div>
                @endif
            </div>

            <dl class="px-6 py-3 border-t border-slate-100 bg-slate-50/60 grid grid-cols-1 sm:grid-cols-3 gap-3 text-[11px]">
                <div>
                    <dt class="font-bold uppercase tracking-[.1em] text-slate-400">Dibuat</dt>
                    <dd class="text-slate-600 mt-0.5">{{ $r->created_at->locale('id')->translatedFormat('d M Y, H:i') }}</dd>
                </div>
                <div>
                    <dt class="font-bold uppercase tracking-[.1em] text-slate-400">Terakhir diedit</dt>
                    <dd class="text-slate-600 mt-0.5">{{ $r->last_edited_at?->locale('id')->translatedFormat('d M Y, H:i') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="font-bold uppercase tracking-[.1em] text-slate-400">Disetujui admin</dt>
                    <dd class="text-slate-600 mt-0.5">
                        {{ $r->approved_at?->locale('id')->translatedFormat('d M Y, H:i') ?? '—' }}
                        @if ($r->approver)
                            <span class="text-slate-400">({{ $r->approver->name }})</span>
                        @endif
                    </dd>
                </div>
            </dl>

            <footer class="px-6 py-3 border-t border-slate-100 flex items-center gap-5">
                @if ($bisaEdit)
                    <button type="button" @click="edit = true"
                            class="text-sm font-semibold text-[#b98a3e] hover:text-[#8f6a2c] transition">
                        {{ $r->status === 'rejected' ? 'Edit & Kirim Ulang' : 'Edit Draft' }}
                    </button>
                @else
                    <span class="text-xs text-slate-400 italic">
                        Tidak dapat diedit (sudah dikirim / tayang)
                    </span>
                @endif

                @if ($r->status !== 'approved')
                    <form method="POST" action="{{ route('member.renungan.destroy', $r) }}"
                          onsubmit="return confirm('Hapus renungan ini? Tindakan ini tidak bisa dibatalkan.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-sm font-semibold text-slate-500 hover:text-red-600 transition">Hapus</button>
                    </form>
                @endif
            </footer>
        </div>

        {{-- ----- MODE EDIT (HANYA JIKA DRAFT / REJECTED) ----- --}}
        @if ($bisaEdit)
            <form x-show="edit" x-cloak method="POST" action="{{ route('member.renungan.update', $r) }}">
                @csrf @method('PUT')
                <input type="hidden" name="renungan_id" value="{{ $r->id }}">

                <div class="px-6 py-5 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="name-{{ $r->id }}" class="{{ $kelasLabel }}">Nama</label>
                            <input id="name-{{ $r->id }}" type="text" name="name" required maxlength="255"
                                   value="{{ $bukaEdit ? old('name', $r->name) : $r->name }}"
                                   class="{{ $kelasInput }}">
                            @if ($bukaEdit && $errors->has('name'))
                                <p class="mt-1.5 text-sm text-red-600">{{ $errors->first('name') }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="title-{{ $r->id }}" class="{{ $kelasLabel }}">Judul Renungan</label>
                            <input id="title-{{ $r->id }}" type="text" name="title" required maxlength="255"
                                   value="{{ $bukaEdit ? old('title', $r->title) : $r->title }}"
                                   class="{{ $kelasInput }}">
                            @if ($bukaEdit && $errors->has('title'))
                                <p class="mt-1.5 text-sm text-red-600">{{ $errors->first('title') }}</p>
                            @endif
                        </div>
                    </div>

                    @php($isiEdit = $bukaEdit ? old('content', $r->content) : $r->content)
                    <div x-data="{ isi: @js($isiEdit) }">
                        <label for="content-{{ $r->id }}" class="{{ $kelasLabel }}">Renungan</label>
                        <textarea id="content-{{ $r->id }}" name="content" rows="10" required maxlength="5000" x-model="isi"
                                  class="{{ $kelasInput }} leading-relaxed">{{ $isiEdit }}</textarea>

                        <div class="flex items-center justify-between mt-1.5">
                            @if ($bukaEdit && $errors->has('content'))
                                <p class="text-sm text-red-600">{{ $errors->first('content') }}</p>
                            @else
                                <span></span>
                            @endif
                            <span class="text-[11px] text-slate-400 tabular-nums" x-text="isi.length + ' / 5000'"></span>
                        </div>
                    </div>

                    <p class="text-xs text-slate-400">
                        Tanggal renungan tetap {{ $r->written_on->locale('id')->translatedFormat('d F Y') }} dan tidak berubah saat diedit.
                    </p>
                </div>

                <div class="px-6 py-3 border-t border-slate-100 bg-slate-50/60 flex flex-wrap items-center justify-end gap-3">
                    <button type="button" @click="edit = false" class="text-sm font-semibold text-slate-500 hover:text-slate-700 transition mr-auto">
                        Batal
                    </button>
                    <button type="submit" name="action" value="draft"
                            class="px-5 py-2 rounded-full text-xs font-semibold text-[#16233f] bg-slate-200 hover:bg-slate-300 transition">
                        Simpan Draft
                    </button>
                    <button type="submit" name="action" value="submit"
                            class="px-6 py-2 rounded-full text-xs font-semibold text-white transition hover:brightness-110" style="{{ $gayaTombol }}">
                        Kirim ke Admin
                    </button>
                </div>
            </form>
        @endif
    </article>
@empty
    <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center">
        <p class="serif text-xl font-bold text-[#16233f]">Belum ada renungan</p>
        <p class="text-sm text-slate-500 mt-1">Renungan pertamamu akan muncul di sini setelah disimpan atau dikirim.</p>
    </div>
@endforelse

@if ($renungans instanceof \Illuminate\Contracts\Pagination\Paginator)
    <div class="mt-6">{{ $renungans->links() }}</div>
@endif
@endsection