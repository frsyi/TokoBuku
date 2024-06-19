<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        if (Auth::user()->is_admin) {
            $transactions = Transaction::with('user')->get();
        } else {
            $transactions = Transaction::where('user_id', Auth::user()->id)->get();
        }

        // Decode items JSON
        foreach ($transactions as $transaction) {
            $transaction->items = json_decode($transaction->items, true);
        }

        return view('transaction.index', compact('transactions'));
    }

    public function create()
    {
        $carts = Cart::where('user_id', Auth::id())->get();
        $totalPrice = $carts->sum(function ($cart) {
            return $cart->book->price * $cart->count;
        });

        return view('transaction.create', compact('carts', 'totalPrice'));
    }

    public function show($id)
    {
        $transaction = Transaction::with('user')->findOrFail($id);
        $items = json_decode($transaction->items, true);

        return view('transaction.detail', compact('transaction', 'items'));
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();
        $carts = Cart::where('user_id', $user->id)->get();
        $totalPrice = $carts->sum(function ($cart) {
            return $cart->book->price * $cart->count;
        });

        // Validasi upload bukti pembayaran
        $request->validate([
            'payment_proof' => 'required|file|max:10240', // maksimum 10MB
        ]);

        // Mengatur penyimpanan bukti pembayaran
        $paymentProof = null;
        if ($request->hasFile('payment_proof')) {
            $paymentProof = $request->file('payment_proof')->store('payment_proofs', 'public');
        } else {
            return back()->with('error', 'Anda harus mengunggah bukti pembayaran sebelum melakukan checkout.');
        }

        // Buat transaksi baru
        $items = $carts->map(function ($cart) {
            return [
                'book_id' => $cart->book_id,
                'book_title' => $cart->book->title,
                'book_price' => $cart->book->price,
                'count' => $cart->count
            ];
        })->toJson();

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'items' => $items,
            'payment_proof' => $paymentProof,
            'order_status' => false,
            'confirmation' => false,
            'total_price' => $totalPrice,
        ]);

        // Hapus keranjang setelah checkout
        Cart::where('user_id', $user->id)->delete();

        return redirect()->route('transaction.index')
            ->with('success', 'Checkout berhasil, pesanan akan segera diproses.');
    }

    public function updateTrackingNumber(Request $request, $id)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255',
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->tracking_number = $request->tracking_number;
        $transaction->save();

        return redirect()->route('transaction.index', $id)->with('success', 'Tracking number updated successfully!');
    }

    public function complete(Transaction $transaction)
    {
        if (auth()->user()->id == $transaction->user_id) {
            $transaction->update(['is_complete' => true]);
            return redirect()->route('transaction.index')->with('success', 'Transaction marked as complete!');
        }

        return redirect()->route('transaction.index')->with('danger', 'You are not authorized to complete this!');
    }

    public function uncomplete(Transaction $transaction)
    {
        if (auth()->user()->id == $transaction->user_id) {
            $transaction->update(['is_complete' => false]);
            return redirect()->route('transaction.index')->with('success', 'Order uncompleted successfully!');
        }

        return redirect()->route('transaction.index')->with('danger', 'You are not authorized to uncomplete this!');
    }
}
