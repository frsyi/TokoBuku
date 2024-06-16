<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::all();
        return view('payment.create', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $book = Book::find($id);
        $payment_date = Carbon::now();

        //cek validasi
        $check_payment = Payment::where('user_id', Auth::user()->id)->where('status', 0)->first();

        //simpan ke database payment
        if (empty($check_payment)) {
            $payment = new Payment();
            $payment->user_id = Auth::user()->id;
            $payment->created_at = $payment_date;
            $payment->total_price = 0;
            $payment->status = 0;
            $payment->save();
        }

        //simpan ke database order
        $new_payment = Payment::where('user_id', Auth::user()->id)->where('status', 0)->first();

        //cek order
        $check_order = Order::where('book_id', $book->id)->where('payment_id', $new_payment->id)->first();
        if (empty($check_order)) {
            $order = new Order();
            $order->book_id = $book->id;
            $order->payment_id = $new_payment->id;
            $order->count = $request->count;
            $order->total_price = $book->price * $request->count;
            $order->save();
        } else {
            $order = Order::where('book_id', $book->id)->where('payment_id', $new_payment->id)->first();
            $order->count = $order->count + $request->count;

            //harga sekarang
            $new_order_price = $book->price * $request->count;
            $order->total_price = $order->total_price + $new_order_price;
            $order->update();
        }

        //jumlah total
        // Recalculate the total price from the orders
        $total_price = Order::where('payment_id', $new_payment->id)->sum('total_price');
        $payment = Payment::where('user_id', Auth::user()->id)->where('status', 0)->first();
        $payment->total_price = $total_price;
        $payment->update();

        // Tambahkan pesan sukses ke session
        return redirect()->route('dashboard')->with('success', 'Order created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::where('id', $id)->first();
        return view('payment.index', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::where('id', $id)->first();

        $payment = Payment::where('id', $order->payment_id)->first();
        $payment->total_price = $payment->total_price - $order->total_price;
        $payment->update();

        $order->delete();

        return redirect()->route('order.index')->with('success', 'Order deleted successfully!');
    }

    public function payment(Request $request)
    {
        // Simpan data payment di tabel order
        $payments = $request->user()->payments;

        foreach ($payments as $payment) {
            Order::create([
                'user_id' => $payment->user_id,
                'order_id' => $payment->id,
                'count' => $payment->count,
                'total_price' => $payment->total_price,
            ]);
        }

        // Arahkan ke halaman order
        return redirect()->route('order.index')->with('success', 'Payment completed successfully!');
    }

    public function detail($id)
    {
        // Memuat relasi dengan user dan order.book untuk halaman detail
        $payment = Payment::with('user', 'orders.book')->findOrFail($id);
        return view('order.detail', compact('payment'));
    }

    public function updateTrackingNumber(Request $request, $id)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->tracking_number = $request->tracking_number;
        $payment->save();

        return redirect()->route('payment.detail', $id)->with('success', 'Tracking number updated successfully!');
    }

    public function complete(Payment $payment)
    {
        if (auth()->user()->id == $payment->user_id) {
            $payment->update(['is_complete' => true]);
            return redirect()->route('order.history')->with('success', 'Order marked as complete!');
        }

        return redirect()->route('order.history')->with('danger', 'You are not authorized to complete this!');
    }

    public function uncomplete(Payment $payment)
    {
        if (auth()->user()->id == $payment->user_id) {
            $payment->update([
                'is_complete' => false,
            ]);

            return redirect()->route('order.history')->with('success', 'uncompleted successfully!');
        }

        return redirect()->route('order.history')->with('danger', 'You are not authorized to uncomplete this!');
    }
}
