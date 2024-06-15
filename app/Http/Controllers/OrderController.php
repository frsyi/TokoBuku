<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return view('order.create', compact('orders'));
    }

    public function show($id)
    {
        $book = Book::where('id', $id)->first();
        return view('order.index', compact('book'));
    }

    public function store(Request $request, $id)
    {
        $book = Book::find($id);
        $order_date = Carbon::now();

        //cek validasi
        $check_order = Order::where('user_id', Auth::user()->id)->where('status', 0)->first();

        //simpan ke database order
        if (empty($check_order)) {
            $order = new Order();
            $order->user_id = Auth::user()->id;
            $order->created_at = $order_date;
            $order->total_price = 0;
            $order->status = 0;
            $order->save();
        }

        //simpan ke database transaction
        $new_order = Order::where('user_id', Auth::user()->id)->where('status', 0)->first();

        //cek transaction
        $check_transaction = Transaction::where('book_id', $book->id)->where('order_id', $new_order->id)->first();
        if (empty($check_transaction)) {
            $transaction = new Transaction();
            $transaction->book_id = $book->id;
            $transaction->order_id = $new_order->id;
            $transaction->amount = $request->amount;
            $transaction->total_price = $book->price * $request->amount;
            $transaction->save();
        } else {
            $transaction = Transaction::where('book_id', $book->id)->where('order_id', $new_order->id)->first();
            $transaction->amount = $transaction->amount + $request->amount;

            //harga sekarang
            $new_transaction_price = $book->price * $request->amount;
            $transaction->total_price = $transaction->total_price + $new_transaction_price;
            $transaction->update();
        }

        //jumlah total
        // Recalculate the total price from the transactions
        $total_price = Transaction::where('order_id', $new_order->id)->sum('total_price');
        $order = Order::where('user_id', Auth::user()->id)->where('status', 0)->first();
        $order->total_price = $total_price;
        $order->update();

        // Tambahkan pesan sukses ke session
        return redirect()->route('dashboard')->with('success', 'Order created successfully!');
    }


    public function destroy($id)
    {
        $transaction = Transaction::where('id', $id)->first();

        $order = Order::where('id', $transaction->order_id)->first();
        $order->total_price = $order->total_price - $transaction->total_price;
        $order->update();

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Order deleted successfully!');
    }

    public function payment(Request $request)
    {
        // Simpan data order di tabel transaksi
        $orders = $request->user()->orders;

        foreach ($orders as $order) {


            Transaction::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                // 'book_title' => $order->book_title,
                'amount' => $order->amount,
                // 'unit_price' => $order->unit_price,
                'total_price' => $order->total_price,
            ]);
        }

        // Arahkan ke halaman transaksi
        return redirect()->route('transactions.index')->with('success', 'Payment completed successfully!');
    }

    public function detail($id)
    {
        // Memuat relasi dengan user dan transactions.book untuk halaman detail
        $order = Order::with('user', 'transactions.book')->findOrFail($id);
        return view('transactions.detail', compact('order'));
    }

    public function updateTrackingNumber(Request $request, $id)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255',
        ]);

        $order = Order::findOrFail($id);
        $order->tracking_number = $request->tracking_number;
        $order->save();

        return redirect()->route('order.detail', $id)->with('success', 'Tracking number updated successfully!');
    }

    public function complete(Order $order)
    {
        if (auth()->user()->id == $order->user_id) {
            $order->update(['is_complete' => true]);
            return redirect()->route('transactions.history')->with('success', 'Order marked as complete!');
        }

        return redirect()->route('transactions.history')->with('danger', 'You are not authorized to complete this!');
    }

    public function uncomplete(Order $order)
    {
        if (auth()->user()->id == $order->user_id) {
            $order->update([
                'is_complete' => false,
            ]);

            return redirect()->route('transactions.history')->with('success', 'uncompleted successfully!');
        }

        return redirect()->route('transactions.history')->with('danger', 'You are not authorized to uncomplete this!');
    }
}
