@extends('layouts.admin')

@section('content')
@php
    $tabs = [
        'pending'  => ['Menunggu', $counts['pending']],
        'tampil'   => ['Tampil', $counts['tampil']],
        'hidden'   => ['Disembunyikan', null],
        'expired'  => ['Kedaluwarsa', null],
        'rejected' => ['Ditolak', null],
        'semua'    => ['Semua', null],
    ];
@endphp

<div class="mb-6">
    <h3 class="text-lg font-semibold text-gray-800">Moderasi Renungan</h3>
    <p class="text-xs text-gray-500 mt-0.5">Setujui renungan anggota dan atur berapa lama renungan itu tampil di halaman anggota lain.</p>
</div>

@if (session('success'))
    <div class="mb-6 p-3 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-xs rounded">
        {{ session('success') }}
    </div>
@endif

{{-- ===== TAB ===== --}}
<nav class="flex flex-wrap gap-2 mb-6">
    @foreach ($tabs as $key => [$label, $jumlah])
        <a href="{{ route('admin.renungan.index', ['tab' => $key]) }}"
           class="px-4 py-2 rounded-lg text-xs font-medium transition {{ $tab === $key ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
            {{ $label }}
            @if ($jumlah !== null)
                <span class="{{ $tab === $key ? 'text-indigo-200' : 'text-gray-400' }}">({{ $jumlah }})</span>
            @endif
        </a>
    @endforeach
</nav>

{{-- ===== DAFTAR ===== --}}
@forelse ($renungans as $r)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-5">

        <div class="flex flex-wrap items-center justify-between gap-2 px-6 py-4 border-b">
            <div class="min-w-0">
                <p class="font-semibold text-gray-800 truncate">{{ $r->name }}</p>
                <p class="text-xs text-gray-500 truncate">
                    {{ $r->user?->email ?? 'akun terhapus' }}
                    &middot; {{ $r->written_on->locale('id')->translatedFormat('d F Y') }}
                </p>
            </div>

            <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full flex-none {{ $r->statusColor() }}">
                {{ $r->statusLabel() }}
            </span>
        </div>

        <div class="px-6 py-5">
            <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $r->content }}</p>

            @if ($r->status === 'rejected' && $r->reject_reason)
                <p class="mt-4 p-3 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs rounded">
                    <span class="font-semibold">Alasan penolakan:</span> {{ $r->reject_reason }}
                </p>
            @endif
        </div>

        {{-- Jejak waktu --}}
        <dl class="px-6 py-3 border-t bg-gray-50 grid grid-cols-2 lg:grid-cols-4 gap-3 text-[11px]">
            <div>
                <dt class="font-semibold text-gray-400 uppercase tracking-wide">Dibuat</dt>
                <dd class="text-gray-600 mt-0.5">{{ $r->created_at->locale('id')->translatedFormat('d M Y, H:i') }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-400 uppercase tracking-wide">Diedit peserta</dt>
                <dd class="text-gray-600 mt-0.5">{{ $r->last_edited_at?->locale('id')->translatedFormat('d M Y, H:i') ?? '—' }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-400 uppercase tracking-wide">Disetujui</dt>
                <dd class="text-gray-600 mt-0.5">
                    {{ $r->approved_at?->locale('id')->translatedFormat('d M Y, H:i') ?? '—' }}
                    @if ($r->approver) <span class="text-gray-400">({{ $r->approver->name }})</span> @endif
                </dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-400 uppercase tracking-wide">Disembunyikan</dt>
                <dd class="text-gray-600 mt-0.5">
                    {{ $r->hidden_at?->locale('id')->translatedFormat('d M Y, H:i') ?? '—' }}
                    @if ($r->hider) <span class="text-gray-400">({{ $r->hider->name }})</span> @endif
                </dd>
            </div>
        </dl>

        {{-- Aksi --}}
        <div class="px-6 py-4 border-t flex flex-wrap items-end gap-x-6 gap-y-4">

            {{-- Setujui / perbarui masa tampil --}}
            <form method="POST"
                  action="{{ $r->status === 'approved' ? route('admin.renungan.extend', $r) : route('admin.renungan.approve', $r) }}"
                  class="flex items-end gap-2">
                @csrf
                <div>
                    <label for="days-{{ $r->id }}" class="block text-xs font-medium text-gray-700 mb-1">
                        Lama tampil (hari)
                    </label>
                    <input id="days-{{ $r->id }}" type="number" name="display_days" min="1" max="365"
                           value="{{ $r->display_days ?? \App\Models\Renungan::DEFAULT_DISPLAY_DAYS }}"
                           placeholder="kosong = tanpa batas"
                           class="w-44 px-3 py-2 border rounded-lg text-sm">
                </div>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg text-sm shadow transition">
                    {{ $r->status === 'approved' ? 'Perbarui' : 'Setujui' }}
                </button>
            </form>

            {{-- Sembunyikan / tampilkan lagi --}}
            @if ($r->hidden_at)
                <form method="POST" action="{{ route('admin.renungan.unhide', $r) }}">
                    @csrf
                    <button type="submit" class="text-xs font-medium text-indigo-600 hover:text-indigo-800 pb-2.5">
                        Tampilkan lagi
                    </button>
                </form>
            @elseif ($r->status === 'approved')
                <form method="POST" action="{{ route('admin.renungan.hide', $r) }}">
                    @csrf
                    <button type="submit" class="text-xs font-medium text-amber-700 hover:text-amber-900 pb-2.5">
                        Sembunyikan
                    </button>
                </form>
            @endif

            {{-- Tolak --}}
            @if ($r->status !== 'rejected')
                <form method="POST" action="{{ route('admin.renungan.reject', $r) }}" class="flex items-end gap-2 ml-auto">
                    @csrf
                    <div>
                        <label for="reason-{{ $r->id }}" class="block text-xs font-medium text-gray-700 mb-1">
                            Alasan penolakan
                        </label>
                        <input id="reason-{{ $r->id }}" type="text" name="reject_reason" maxlength="255"
                               placeholder="Opsional" class="w-56 px-3 py-2 border rounded-lg text-sm">
                    </div>
                    <button type="submit" class="px-5 py-2 border border-red-200 text-red-600 hover:bg-red-50 font-medium rounded-lg text-sm transition">
                        Tolak
                    </button>
                </form>
            @endif
        </div>
    </div>
@empty
    <div class="text-center py-12 border-2 border-dashed rounded-xl text-gray-400 text-sm">
        Tidak ada renungan di kategori ini.
    </div>
@endforelse

<div class="mt-6">{{ $renungans->links() }}</div>
@endsection