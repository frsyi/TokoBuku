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
    //     public function index()
    // {
    //     $transactions = Transaction::with('book', 'order')->get();
    //     return view('transactions.index', compact('transactions'));
    // }


}
