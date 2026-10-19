@extends('layouts.member')

@section('title', 'Renungan Harian')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900">Renungan Harian</h1>
    <p class="text-sm text-slate-500 mt-1">Tuliskan renungan harianmu.</p>
</div>

<form method="POST" action="{{ route('member.renungan.store') }}" class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-5">
    @csrf

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama</label>
            <input id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required maxlength="255"
                   class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="written_on" class="block text-sm font-medium text-slate-700 mb-1">Tanggal</label>
            <input id="written_on" type="date" name="written_on" value="{{ old('written_on', now()->toDateString()) }}" required
                   class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
            @error('written_on') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label for="content" class="block text-sm font-medium text-slate-700 mb-1">Renungan</label>
        <textarea id="content" name="content" rows="7" required maxlength="5000"
                  placeholder="Tulis renunganmu di sini..."
                  class="w-full px-4 py-3 border border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('content') }}</textarea>
        @error('content') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="text-right">
        <button type="submit" class="bg-indigo-600 text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-indigo-700 transition">
            Simpan
        </button>
    </div>
</form>

@if ($renungans->isNotEmpty())
    <h2 class="text-lg font-semibold text-slate-900 mt-12 mb-4">Renungan Terakhirmu</h2>
    <div class="space-y-4">
        @foreach ($renungans as $r)
            <article class="bg-white rounded-xl border border-slate-200 p-5">
                <header class="flex items-center justify-between text-xs text-slate-500 mb-2">
                    <span class="font-semibold text-slate-700">{{ $r->name }}</span>
                    <time datetime="{{ $r->written_on->toDateString() }}">{{ $r->written_on->locale('id')->translatedFormat('l, d F Y') }}</time>
                </header>
                <p class="text-sm text-slate-700 leading-relaxed">{!! nl2br(e($r->content)) !!}</p>
            </article>
        @endforeach
    </div>
@endif
@endsection
