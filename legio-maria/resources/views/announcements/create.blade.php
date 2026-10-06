@extends('layouts.app')
@section('title', 'Buat Pengumuman')

@section('content')
<div class="lm-card" style="max-width:520px;">
    <form method="POST" action="{{ route('announcements.store') }}">
        @csrf
        <div class="lm-field">
            <label>Judul</label>
            <input type="text" name="title" required>
        </div>
        <div class="lm-field">
            <label>Tanggal kegiatan (opsional)</label>
            <input type="date" name="event_date">
        </div>
        <div class="lm-field">
            <label>Isi pengumuman</label>
            <textarea name="content" rows="4" required></textarea>
        </div>
        <button class="lm-btn lm-btn-primary" type="submit">Publikasikan</button>
    </form>
</div>
@endsection
