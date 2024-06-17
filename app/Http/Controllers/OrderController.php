<?php

namespace App\Http\Controllers;

use App\Models\Book;
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

    public function show(string $id)
    {
        $book = Book::where('id', $id)->first();
        return view('order.index', compact('book'));
    }
}
