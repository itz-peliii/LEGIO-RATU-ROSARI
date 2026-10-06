@extends('layouts.app')
@section('title', 'Pengumuman')
@section('subtitle', 'Informasi dan agenda kegiatan komunitas')

@section('content')
<div style="margin-bottom:16px;">
    <a href="{{ route('announcements.create') }}" class="lm-btn lm-btn-primary">+ Buat pengumuman</a>
</div>

@foreach ($announcements as $a)
<div class="lm-card">
    <div style="display:flex; justify-content:space-between;">
        <h3>{{ $a->title }}</h3>
        <form action="{{ route('announcements.destroy', $a) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
            @csrf @method('DELETE')
            <button class="lm-btn lm-btn-danger" style="padding:4px 12px; font-size:12px;">Hapus</button>
        </form>
    </div>
    @if ($a->event_date)
        <p style="font-size:12.5px; color:#4FA79A; font-weight:700; margin:0 0 8px;">{{ \Carbon\Carbon::parse($a->event_date)->format('d M Y') }}</p>
    @endif
    <p style="font-size:14px; color:#3a565f;">{{ $a->content }}</p>
</div>
@endforeach

<div style="margin-top:12px;">{{ $announcements->links() }}</div>
@endsection
