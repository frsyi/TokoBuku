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
    public function index()
    {
        $payment = Payment::where('user_id', Auth::user()->id)
            ->where('status', 0)
            ->first();

        if (!$payment) {
            return redirect()->route('dashboard')->with('error', 'Tidak ada pembayaran aktif.');
        }

        $orders = Order::where('payment_id', $payment->id)->get();
        return view('payment.create', compact('payment', 'orders'));
    }

    public function store(Request $request, $id)
    {
        $book = Book::find($id);
        $payment_date = Carbon::now();

        // Check payment validation
        $check_payment = Payment::where('user_id', Auth::user()->id)->where('status', 0)->first();

        // Save payment to database
        if (empty($check_payment)) {
            $payment = new Payment();
            $payment->user_id = Auth::user()->id;
            $payment->created_at = $payment_date;
            $payment->total_price = 0;
            $payment->status = 0;
            $payment->save();
        }

        // Save order to database
        $new_payment = Payment::where('user_id', Auth::user()->id)->where('status', 0)->first();

        // Check order
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
            $order->count += $request->count;

            // Update price
            $new_order_price = $book->price * $request->count;
            $order->total_price += $new_order_price;
            $order->update();
        }

        // Recalculate the total price
        $total_price = Order::where('payment_id', $new_payment->id)->sum('total_price');
        $payment = Payment::where('user_id', Auth::user()->id)->where('status', 0)->first();
        $payment->total_price = $total_price;
        $payment->update();

        return redirect()->route('dashboard')->with('success', 'Order created successfully!');
    }

    public function show($id)
    {
        $payment = Payment::with('user', 'orders.book')->findOrFail($id);
        return view('payment.detail', compact('payment'));
    }

    public function destroy($id)
    {
        $order = Order::where('id', $id)->first();

        $payment = Payment::where('id', $order->payment_id)->first();
        $payment->total_price -= $order->total_price;
        $payment->update();

        $order->delete();

        return redirect()->route('payment.index')->with('success', 'Order deleted successfully!');
    }

    public function uploadProof(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $payment = Payment::findOrFail($id);

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $path = $file->store('payment_proofs', 'public');

            $payment->payment_proof = $path;
            $payment->save();
        }

        return redirect()->route('payment.show', $id)->with('success', 'Payment proof uploaded successfully!');
    }

    public function updateTrackingNumber(Request $request, $id)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->tracking_number = $request->tracking_number;
        $payment->save();

        return redirect()->route('payment.show', $id)->with('success', 'Tracking number updated successfully!');
    }

    public function complete(Payment $payment)
    {
        if (auth()->user()->id == $payment->user_id) {
            $payment->update(['is_complete' => true]);
            return redirect()->route('payment.history')->with('success', 'Order marked as complete!');
        }

        return redirect()->route('payment.history')->with('danger', 'You are not authorized to complete this!');
    }

    public function uncomplete(Payment $payment)
    {
        if (auth()->user()->id == $payment->user_id) {
            $payment->update(['is_complete' => false]);
            return redirect()->route('payment.history')->with('success', 'Order uncompleted successfully!');
        }

        return redirect()->route('payment.history')->with('danger', 'You are not authorized to uncomplete this!');
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

        return view('payment.history', compact('payments'));
    }
}
