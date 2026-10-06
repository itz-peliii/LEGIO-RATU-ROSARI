@extends('layouts.app')
@section('title', 'Kunjungan Pelayanan')
@section('subtitle', 'Usulan & penjadwalan kunjungan orang sakit / berduka')

@section('content')
<div style="margin-bottom:16px;">
    <a href="{{ route('visits.create') }}" class="lm-btn lm-btn-primary">+ Ajukan kunjungan</a>
</div>

<div class="lm-card">
    <table class="lm-table">
        <thead><tr><th>Nama umat</th><th>Kategori</th><th>Status</th><th>Bertugas</th><th></th></tr></thead>
        <tbody>
        @foreach ($requests as $r)
            <tr>
                <td>{{ $r->beneficiary_name }}</td>
                <td>{{ ucfirst($r->category) }}</td>
                <td><span class="lm-badge badge-{{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
                <td>
                    @if ($r->schedule)
                        {{ $r->schedule->assignedMember->name }} · {{ $r->schedule->visit_date->format('d M Y') }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if (!$r->schedule)
                        <a href="{{ route('visits.schedule', $r) }}" class="lm-btn lm-btn-ghost" style="padding:6px 14px;">Jadwalkan</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div style="margin-top:12px;">{{ $requests->links() }}</div>
</div>
@endsection
