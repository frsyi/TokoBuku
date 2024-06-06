<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return view('orders.index', compact('orders'));
    }


    public function store(Request $request)
    {
        // Validasi request
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        // Temukan buku berdasarkan ID
        $book = Book::findOrFail($request->book_id);

        // Buat order baru
        $order = Order::create([
            'user_id' => Auth::id(),
            'book_title' => $book->title,
            'amount' => 1, // Asumsikan satu buku per order, Anda bisa tambahkan fitur jumlah jika diperlukan
            'unit_price' => $book->price,
            'total_price' => $book->price * 1,
        ]);

        return redirect()->route('orders.index')->with('success', 'Order created successfully!');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully!');
    }




    public function payment(Request $request)
{
    // Simpan data order di tabel transaksi
    $orders = $request->user()->orders;

    foreach ($orders as $order) {
        

        Transaction::create([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'book_title' => $order->book_title,
            'amount' => $order->amount,
            'unit_price' => $order->unit_price,
            'total_price' => $order->total_price,
        ]);
    }

    // Arahkan ke halaman transaksi
    return redirect()->route('transactions.index')->with('success', 'Payment completed successfully!');
}












    





}
