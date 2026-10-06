@extends('layouts.app')
@section('title', 'Jadwalkan Kunjungan')

@section('content')
<div class="lm-card" style="max-width:480px;">
    <p style="font-size:14px; color:#5b7c86;">Usulan untuk: <strong>{{ $visitRequest->beneficiary_name }}</strong> ({{ $visitRequest->category }})</p>
    <form method="POST" action="{{ route('visits.storeSchedule', $visitRequest) }}">
        @csrf
        <div class="lm-field">
            <label>Anggota yang bertugas</label>
            <select name="assigned_member_id" required>
                @foreach ($members as $mem)
                    <option value="{{ $mem->id }}">{{ $mem->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="lm-field">
            <label>Tanggal kunjungan</label>
            <input type="date" name="visit_date" required>
        </div>
        <button class="lm-btn lm-btn-primary" type="submit">Simpan jadwal</button>
    </form>
</div>
@endsection
