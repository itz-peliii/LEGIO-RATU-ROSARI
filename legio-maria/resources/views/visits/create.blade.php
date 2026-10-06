@extends('layouts.app')
@section('title', 'Ajukan Kunjungan')

@section('content')
<div class="lm-card" style="max-width:520px;">
    <form method="POST" action="{{ route('visits.store') }}">
        @csrf
        <div class="lm-field">
            <label>Diajukan oleh</label>
            <select name="requested_by">
                <option value="">- Pilih anggota (opsional) -</option>
                @foreach ($members as $mem)
                    <option value="{{ $mem->id }}">{{ $mem->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="lm-field">
            <label>Nama umat yang dikunjungi</label>
            <input type="text" name="beneficiary_name" required>
        </div>
        <div class="lm-field">
            <label>Alamat</label>
            <textarea name="address" rows="2"></textarea>
        </div>
        <div class="lm-field">
            <label>Kategori</label>
            <select name="category">
                <option value="sakit">Sakit</option>
                <option value="berduka">Berduka</option>
            </select>
        </div>
        <div class="lm-field">
            <label>Catatan tambahan</label>
            <textarea name="notes" rows="2"></textarea>
        </div>
        <button class="lm-btn lm-btn-primary" type="submit">Ajukan</button>
    </form>
</div>
@endsection
