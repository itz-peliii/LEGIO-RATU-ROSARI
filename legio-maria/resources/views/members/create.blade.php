@extends('layouts.app')
@section('title', isset($member) && $member->exists ? 'Ubah Anggota' : 'Tambah Anggota')

@section('content')
<div class="lm-card" style="max-width:520px;">
    <form method="POST" action="{{ isset($member) && $member->exists ? route('members.update', $member) : route('members.store') }}">
        @csrf
        @if (isset($member) && $member->exists) @method('PUT') @endif

        <div class="lm-field">
            <label>Nama lengkap</label>
            <input type="text" name="name" value="{{ old('name', $member->name ?? '') }}" required>
        </div>
        <div class="lm-field">
            <label>No. HP / WhatsApp</label>
            <input type="text" name="phone" value="{{ old('phone', $member->phone ?? '') }}">
        </div>
        <div class="lm-field">
            <label>Alamat</label>
            <textarea name="address" rows="2">{{ old('address', $member->address ?? '') }}</textarea>
        </div>
        <div class="lm-field">
            <label>Jenis kelamin</label>
            <select name="gender">
                <option value="P" {{ old('gender', $member->gender ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                <option value="L" {{ old('gender', $member->gender ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
            </select>
        </div>
        <div class="lm-field">
            <label>Tanggal bergabung</label>
            <input type="date" name="join_date" value="{{ old('join_date', $member->join_date ?? '') }}">
        </div>
        <div class="lm-field">
            <label>Status</label>
            <select name="status">
                <option value="aktif" {{ old('status', $member->status ?? 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="tidak_aktif" {{ old('status', $member->status ?? '') === 'tidak_aktif' ? 'selected' : '' }}>Tidak aktif</option>
            </select>
        </div>

        <button class="lm-btn lm-btn-primary" type="submit">Simpan</button>
        <a href="{{ route('members.index') }}" class="lm-btn lm-btn-ghost">Batal</a>
    </form>
</div>
@endsection
