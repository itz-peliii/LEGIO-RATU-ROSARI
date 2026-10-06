@extends('layouts.app')
@section('title', 'Kas Komunitas')
@section('subtitle', 'Pembukuan kas sukarela bulanan')

@section('content')
<div class="lm-stats">
    <div class="lm-stat-card">
        <div class="icon-bubble bg-turquoise">⬆️</div>
        <div class="value">Rp {{ number_format($saldoMasuk, 0, ',', '.') }}</div>
        <div class="label">Total pemasukan</div>
    </div>
    <div class="lm-stat-card">
        <div class="icon-bubble bg-blush">⬇️</div>
        <div class="value">Rp {{ number_format($saldoKeluar, 0, ',', '.') }}</div>
        <div class="label">Total pengeluaran</div>
    </div>
    <div class="lm-stat-card">
        <div class="icon-bubble bg-deep">💰</div>
        <div class="value">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
        <div class="label">Saldo akhir</div>
    </div>
</div>

<div style="margin-bottom:16px;">
    <a href="{{ route('cash.create') }}" class="lm-btn lm-btn-primary">+ Catat transaksi</a>
</div>

<div class="lm-card">
    <table class="lm-table">
        <thead><tr><th>Tanggal</th><th>Jenis</th><th>Kategori</th><th>Keterangan</th><th>Jumlah</th><th></th></tr></thead>
        <tbody>
        @foreach ($transactions as $t)
            <tr>
                <td>{{ \Carbon\Carbon::parse($t->transaction_date)->format('d M Y') }}</td>
                <td><span class="lm-badge badge-{{ $t->type }}">{{ $t->type === 'masuk' ? 'Masuk' : 'Keluar' }}</span></td>
                <td>{{ $t->category ?? '-' }}</td>
                <td>{{ $t->description ?? '-' }}</td>
                <td>Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                <td>
                    <form action="{{ route('cash.destroy', $t) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
                        @csrf @method('DELETE')
                        <button class="lm-btn lm-btn-danger" style="padding:6px 14px;">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div style="margin-top:12px;">{{ $transactions->links() }}</div>
</div>
@endsection
