<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $payment = Payment::where('user_id', Auth::user()->id)->where('status', 0)->first();
        $orders = Order::where('payment_id', $payment->id)->get();
        return view('order.index', compact('payment', 'orders'));
    }


    public function history()
    {
        if (Auth::user()->is_admin) {
            // Jika admin, ambil semua payment
            $payments = Payment::with('user', 'orders')->get();
        } else {
            // Jika bukan admin, ambil payment berdasarkan user yang sedang login
            $payments = Payment::with('orders')->where('user_id', Auth::user()->id)->get();
        }

        return view('order.history', compact('payments'));
    }
}
