<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        return view('orders.create');
    }

    // Menyimpan pesanan baru ke database
    public function store(Request $request)
    {
        // Validasi data yang diterima dari form
        $validatedData = $request->validate([
            'title' => 'required|string',
            'amount' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);

        // Membuat pesanan baru
        $order = new Order();
        $order->title = $validatedData['title'];
        $order->amount = $validatedData['amount'];
        $order->unit_price = $validatedData['unit_price'];
        $order->total_price = $validatedData['amount'] * $validatedData['unit_price'];
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    // Menghapus pesanan dari database
    public function delete(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
