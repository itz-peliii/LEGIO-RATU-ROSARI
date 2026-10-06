@extends('layouts.app')
@section('title', 'Catat Transaksi Kas')

@section('content')
<div class="lm-card" style="max-width:480px;">
    <form method="POST" action="{{ route('cash.store') }}">
        @csrf
        <div class="lm-field">
            <label>Jenis transaksi</label>
            <select name="type">
                <option value="masuk">Pemasukan</option>
                <option value="keluar">Pengeluaran</option>
            </select>
        </div>
        <div class="lm-field">
            <label>Kategori (mis. iuran bulanan, bantuan sosial)</label>
            <input type="text" name="category">
        </div>
        <div class="lm-field">
            <label>Jumlah (Rp)</label>
            <input type="number" name="amount" min="0" step="1000" required>
        </div>
        <div class="lm-field">
            <label>Tanggal</label>
            <input type="date" name="transaction_date" required>
        </div>
        <div class="lm-field">
            <label>Keterangan</label>
            <textarea name="description" rows="2"></textarea>
        </div>
        <button class="lm-btn lm-btn-primary" type="submit">Simpan</button>
    </form>
</div>
@endsection
