<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        // Debug untuk memeriksa file yang diunggah
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            Log::info('File payment_proof uploaded: ' . $file->getClientOriginalName());

            // Mengatur penyimpanan bukti pembayaran
            try {
                $paymentProof = $file->store('payment_proofs', 'public');
                Log::info('Payment proof stored at: ' . $paymentProof);
            } catch (\Exception $e) {
                Log::error('Error storing payment proof: ' . $e->getMessage());
                return back()->with('error', 'Terjadi kesalahan saat menyimpan bukti pembayaran.');
            }
        } else {
            Log::error('No payment proof file uploaded.');
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

        // Simpan transaksi ke database
        try {
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'items' => $items,
                'payment_proof' => $paymentProof,
                'order_status' => false,
                'confirmation' => false,
                'total_price' => $totalPrice,
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating transaction: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membuat transaksi.');
        }

        // Hapus keranjang setelah checkout berhasil
        Cart::where('user_id', $user->id)->delete();

        // Redirect dengan pesan sukses
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

    public function delivered(Transaction $transaction)
    {
        if (auth()->user()->is_admin) {
            $transaction->update(['order_status' => true]);
            return redirect()->route('transaction.index')->with('success', 'Transaction marked as delivered!');
        } else {
            return redirect()->route('transaction.index')->with('danger', 'You are not authorized to mark this transaction as delivered!');
        }
    }

    public function processed(Transaction $transaction)
    {
        if (auth()->user()->is_admin) {
            $transaction->update(['order_status' => false]);
            return redirect()->route('transaction.index')->with('success', 'Transaction marked as processed!');
        } else {
            return redirect()->route('transaction.index')->with('danger', 'You are not authorized to mark this transaction as processed!');
        }
    }

    public function received(Transaction $transaction)
    {
        if (!auth()->user()->is_admin) {
            $transaction->update(['confirmation' => true]);
            return redirect()->route('transaction.index')->with('success', 'Transaction marked as received!');
        } else {
            return redirect()->route('transaction.index')->with('danger', 'You are not authorized to mark this transaction as received!');
        }
    }

    public function unreceived(Transaction $transaction)
    {
        if (!auth()->user()->is_admin) {
            $transaction->update(['confirmation' => false]);
            return redirect()->route('transaction.index')->with('success', 'Transaction marked as unreceived!');
        } else {
            return redirect()->route('transaction.index')->with('danger', 'You are not authorized to mark this transaction as unreceived!');
        }
    }

    // TransactionController.php

public function toggleConfirmation(Transaction $transaction)
{
    $transaction->confirmation = !$transaction->confirmation;
    $transaction->save();

    return back();
}

}
