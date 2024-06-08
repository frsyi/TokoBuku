<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->role == 'is_admin') {
            // Jika admin, ambil semua transaksi
            $transactions = Transaction::all();
        } else {
            // Jika bukan admin, ambil transaksi berdasarkan user yang sedang login
            $transactions = Transaction::whereHas('order', function($query) {
                $query->where('user_id', Auth::user()->id);
            })->get();
        }

        // Mengirimkan data transaksi ke view transactions.index
        return view('transactions.index', compact('transactions'));
    }
    //     public function index()
    // {
    //     $transactions = Transaction::with('book', 'order')->get();
    //     return view('transactions.index', compact('transactions'));
    // }

    public function history()
    {
        $transactions = Transaction::whereHas('order', function($query) {
            $query->where('user_id', Auth::user()->id);
        })->get();

        // Menampilkan view transactions.history dengan data histories
        return view('transactions.history', compact('transactions'));
    }


}
