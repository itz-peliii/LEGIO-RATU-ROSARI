@extends('layouts.member')

@section('title', 'Renungan Saya')

@section('content')
@php
    $kelasInput  = 'w-full px-4 py-2.5 border border-slate-300 rounded-lg text-sm text-[#16233f] bg-white focus:outline-none focus:ring-2 focus:ring-[#e6cf9b] focus:border-[#b98a3e] transition';
    $kelasLabel  = 'block text-[11px] font-semibold uppercase tracking-[.14em] text-slate-500 mb-1.5';
    $gayaTombol  = 'background: linear-gradient(135deg, #c79646, #b98a3e 55%, #a87c33); box-shadow: 0 10px 22px -12px rgba(185,138,62,.9);';
    $kelasTombol = 'px-7 py-2.5 rounded-full text-sm font-semibold text-white transition hover:brightness-110';
    $kelasDraft  = 'px-6 py-2 rounded-full text-sm font-semibold text-[#16233f] bg-white border border-slate-300 hover:border-[#b98a3e] hover:text-[#b98a3e] transition';

    // Kalau update gagal validasi, kartu yang sedang diedit dibuka kembali otomatis.
    $sedangEdit = old('renungan_id');

    // Kalau store gagal validasi, form tulis dibuka kembali otomatis.
    $bukaForm = ! $sedangEdit && $errors->any();

    $labelBulan = fn ($ym) => \Carbon\Carbon::createFromFormat('Y-m', $ym)->locale('id')->translatedFormat('F Y');
@endphp

<div class="mb-7 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="serif text-3xl font-bold text-[#16233f]">Renungan Saya</h1>
        <p class="text-sm text-slate-500 mt-1">
            Tuliskan renungan harianmu. Renungan baru tampil untuk anggota lain setelah disetujui oleh Admin.
        </p>
    </div>
</div>

<div x-data="{ buka: {{ $bukaForm ? 'true' : 'false' }} }" class="mb-10">

    <button type="button" x-show="!buka" @click="buka = true; $nextTick(() => document.getElementById('content').focus())"
            class="{{ $kelasTombol }} inline-flex items-center gap-2" style="{{ $gayaTombol }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
        Tambah Renungan
    </button>

    <form x-show="buka" x-cloak method="POST" action="{{ route('member.renungan.store') }}"
          class="bg-white rounded-2xl border border-slate-200 shadow-[0_18px_40px_-32px_rgba(22,35,63,.5)] overflow-hidden">

        <div class="flex flex-wrap items-center justify-between gap-2 px-6 py-4 border-b border-slate-100 bg-slate-50/60">
            <h2 class="serif text-xl font-bold text-[#16233f]">Tulis Renungan</h2>
            <p class="text-xs font-semibold text-slate-500">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
        </div>

        <div class="px-6 py-5 space-y-4">
            @csrf

            <div class="sm:max-w-xs">
                <label for="name" class="{{ $kelasLabel }}">Nama</label>
                <input id="name" type="text" name="name" value="{{ $sedangEdit ? auth()->user()->name : old('name', auth()->user()->name) }}" required maxlength="255"
                       class="{{ $kelasInput }}">
                @if (! $sedangEdit && $errors->has('name'))
                    <p class="mt-1.5 text-sm text-red-600">{{ $errors->first('name') }}</p>
                @endif
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

            <div class="flex flex-wrap items-center justify-between gap-4 pt-1">
                <p class="text-xs text-slate-400 leading-relaxed max-w-md">
                    Belum selesai? Simpan sebagai draft dulu, draft tidak dilihat admin maupun anggota lain.
                    Setelah <strong>Kirim</strong>, renungan masuk antrean persetujuan admin.
                </p>
                <div class="flex items-center gap-3 flex-none">
                    <button type="button" @click="buka = false" class="text-sm font-semibold text-slate-500 hover:text-slate-700 transition">Batal</button>
                    <button type="submit" name="aksi" value="draft" class="{{ $kelasDraft }}">Simpan draft</button>
                    <button type="submit" name="aksi" value="kirim" class="{{ $kelasTombol }}" style="{{ $gayaTombol }}">Kirim</button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- ===== RIWAYAT RENUNGAN SENDIRI ===== --}}
<div class="flex flex-wrap items-center gap-4 mb-5">
    <h2 class="serif text-2xl font-bold text-[#16233f] flex-none">Renungan yang sudah saya tulis</h2>
    <span class="h-px flex-1 min-w-[2rem] bg-gradient-to-r from-[#e6cf9b] to-transparent"></span>

    @if ($months->isNotEmpty())
        <form method="GET" action="{{ route('member.renungan') }}" class="relative flex-none">
            <label for="bulan" class="sr-only">Bulan</label>
            <select id="bulan" name="bulan" onchange="this.form.submit()"
                    class="appearance-none bg-none pl-4 pr-11 py-2 border border-slate-300 rounded-lg text-sm font-semibold text-[#16233f] bg-white cursor-pointer hover:border-[#b98a3e] focus:outline-none focus:ring-2 focus:ring-[#e6cf9b] transition">
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
    @php
        $bukaEdit  = (string) $sedangEdit === (string) $r->id;
        $isDraft   = $r->status === 'draft';
        $isBeku    = $r->status === 'approved';
        $labelStatus = $isDraft ? 'Draft' : $r->statusLabel();
        $warnaStatus = $isDraft ? 'bg-slate-100 text-slate-600' : $r->statusColor();
    @endphp

    <article x-data="{ edit: {{ $bukaEdit ? 'true' : 'false' }} }"
             class="bg-white rounded-2xl border {{ $isDraft ? 'border-dashed border-slate-300' : 'border-slate-200' }} shadow-[0_18px_40px_-32px_rgba(22,35,63,.5)] overflow-hidden mb-4">

        <header class="flex flex-wrap items-center justify-between gap-2 px-6 py-4 border-b border-slate-100">
            <time class="text-sm font-semibold text-[#16233f]" datetime="{{ $r->written_on->toDateString() }}">
                {{ $r->written_on->locale('id')->translatedFormat('l, d F Y') }}
            </time>
            <span class="text-[11px] font-bold uppercase tracking-[.1em] px-2.5 py-1 rounded-full {{ $warnaStatus }}">
                {{ $labelStatus }}
            </span>
        </header>

        {{-- ----- MODE BACA ----- --}}
        <div x-show="!edit">
            <div class="px-6 py-5">
                <p class="text-[15px] text-slate-700 leading-[1.8] whitespace-pre-line [overflow-wrap:anywhere]">{{ $r->content }}</p>

                @if ($r->status === 'rejected' && $r->reject_reason)
                    <p class="mt-4 text-xs text-red-700 bg-red-50 border-l-2 border-red-400 px-3 py-2 rounded [overflow-wrap:anywhere]">
                        <span class="font-semibold">Catatan admin:</span> {{ $r->reject_reason }}
                    </p>
                @endif
            </div>

            @unless ($isDraft)
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
            @endunless

            <footer class="px-6 py-3 border-t border-slate-100 flex flex-wrap items-center gap-5">
                @if ($isBeku)
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 10-8 0v4m-2 0h12a1 1 0 011 1v8a1 1 0 01-1 1H6a1 1 0 01-1-1v-8a1 1 0 011-1z"/></svg>
                        Sudah disetujui, tidak bisa diedit
                    </span>
                @else
                    <button type="button" @click="edit = true"
                            class="text-sm font-semibold text-[#b98a3e] hover:text-[#8f6a2c] transition">
                        {{ $isDraft ? 'Lanjutkan' : 'Edit' }}
                    </button>
                @endif

                <form method="POST" action="{{ route('member.renungan.destroy', $r) }}"
                      onsubmit="return confirm('Hapus renungan ini? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-sm font-semibold text-slate-500 hover:text-red-600 transition">Hapus</button>
                </form>
            </footer>
        </div>

        {{-- ----- MODE EDIT (tidak dirender untuk renungan yang sudah disetujui) ----- --}}
        @unless ($isBeku)
            <form x-show="edit" x-cloak method="POST" action="{{ route('member.renungan.update', $r) }}">
                @csrf @method('PUT')
                <input type="hidden" name="renungan_id" value="{{ $r->id }}">

                <div class="px-6 py-5 space-y-4">
                    <div class="sm:max-w-xs">
                        <label for="name-{{ $r->id }}" class="{{ $kelasLabel }}">Nama</label>
                        <input id="name-{{ $r->id }}" type="text" name="name" required maxlength="255"
                               value="{{ $bukaEdit ? old('name', $r->name) : $r->name }}"
                               class="{{ $kelasInput }}">
                        @if ($bukaEdit && $errors->has('name'))
                            <p class="mt-1.5 text-sm text-red-600">{{ $errors->first('name') }}</p>
                        @endif
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
                        @unless ($isDraft)
                            Setelah disimpan, renungan masuk antrean persetujuan admin lagi.
                        @endunless
                    </p>
                </div>

                <div class="px-6 py-3 border-t border-slate-100 bg-slate-50/60 flex items-center justify-end gap-4">
                    <button type="button" @click="edit = false" class="text-sm font-semibold text-slate-500 hover:text-slate-700 transition">
                        Batal
                    </button>
                    @if ($isDraft)
                        <button type="submit" name="aksi" value="draft" class="{{ $kelasDraft }}">Simpan draft</button>
                    @endif
                    <button type="submit" name="aksi" value="kirim" class="px-6 py-2 rounded-full text-sm font-semibold text-white transition hover:brightness-110" style="{{ $gayaTombol }}">
                        {{ $isDraft ? 'Kirim' : 'Simpan' }}
                    </button>
                </div>
            </form>
        @endunless
    </article>
@empty
    <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center">
        <p class="serif text-xl font-bold text-[#16233f]">Belum ada renungan</p>
        <p class="text-sm text-slate-500 mt-1">Renungan pertamamu akan muncul di sini setelah dikirim.</p>
    </div>
@endforelse

@if ($renungans instanceof \Illuminate\Contracts\Pagination\Paginator)
    <div class="mt-6">{{ $renungans->links() }}</div>
@endif
@endsection