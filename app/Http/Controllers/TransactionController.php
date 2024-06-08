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
        $order = Order::where('user_id', Auth::user()->id)->where('status', 0)->first();
        $transactions = Transaction::where('order_id', $order->id)->get();
        return view('transactions.index', compact('order', 'transactions'));
    }


    public function history()
    {
        if (Auth::user()->is_admin) {
            // Jika admin, ambil semua order
            $orders = Order::with('user', 'transactions')->get();
        } else {
            // Jika bukan admin, ambil order berdasarkan user yang sedang login
            $orders = Order::with('transactions')->where('user_id', Auth::user()->id)->get();
        }

        return view('transactions.history', compact('orders'));
    }

    public function show($id)
    {
        // Memuat relasi dengan user dan transactions.book untuk halaman detail
        $order = Order::with('user', 'transactions.book')->findOrFail($id);
        return view('transactions.show', compact('order'));
    }
}
