@extends('layouts.app')
@section('title', 'Pertemuan Baru')

@section('content')
<div class="lm-card" style="max-width:480px;">
    <form method="POST" action="{{ route('attendance.store') }}">
        @csrf
        <div class="lm-field">
            <label>Tanggal pertemuan</label>
            <input type="date" name="meeting_date" required>
        </div>
        <div class="lm-field">
            <label>Topik / agenda (opsional)</label>
            <input type="text" name="topic">
        </div>
        <button class="lm-btn lm-btn-primary" type="submit">Buat & lanjut isi presensi</button>
    </form>
</div>
@endsection
