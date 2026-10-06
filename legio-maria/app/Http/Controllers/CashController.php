<?php

namespace App\Http\Controllers;

use App\Models\CashTransaction;
use Illuminate\Http\Request;

class CashController extends Controller
{
    public function index()
    {
        $transactions = CashTransaction::orderByDesc('transaction_date')->paginate(15);

        $saldoMasuk = CashTransaction::where('type', 'masuk')->sum('amount');
        $saldoKeluar = CashTransaction::where('type', 'keluar')->sum('amount');
        $saldo = $saldoMasuk - $saldoKeluar;

        return view('cash.index', compact('transactions', 'saldoMasuk', 'saldoKeluar', 'saldo'));
    }

    public function create()
    {
        return view('cash.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:masuk,keluar',
            'category' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        CashTransaction::create($data);

        return redirect()->route('cash.index')->with('success', 'Transaksi kas berhasil dicatat.');
    }

    public function destroy(CashTransaction $cash)
    {
        $cash->delete();

        return redirect()->route('cash.index')->with('success', 'Transaksi dihapus.');
    }
}
