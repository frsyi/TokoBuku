<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $transactions = $user->is_admin ?
                Transaction::with('user')->get() :
                Transaction::where('user_id', $user->id)->get();

            foreach ($transactions as $transaction) {
                $transaction->items = json_decode($transaction->items, true);
            }

            return response()->json([
                'status' => 'success',
                'data' => $transactions,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve transactions.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $transaction = Transaction::with('user')->findOrFail($id);
            $transaction->items = json_decode($transaction->items, true);

            return response()->json([
                'status' => 'success',
                'data' => $transaction,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction not found.',
                'error' => $exception->getMessage(),
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $carts = Cart::where('user_id', $user->id)->get();
            $totalPrice = $carts->sum(function ($cart) {
                return $cart->book->price * $cart->count;
            });

            $request->validate([
                'payment_proof' => 'required|file|max:10240', // maksimum 10MB
            ]);

            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                Log::info('File payment_proof uploaded: ' . $file->getClientOriginalName());

                try {
                    $paymentProof = $file->store('payment_proofs', 'public');
                    Log::info('Payment proof stored at: ' . $paymentProof);
                } catch (\Exception $e) {
                    Log::error('Error storing payment proof: ' . $e->getMessage());
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to store payment proof.',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            } else {
                Log::error('No payment proof file uploaded.');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payment proof is required.',
                ], 400);
            }

            $items = $carts->map(function ($cart) {
                return [
                    'book_id' => $cart->book_id,
                    'book_title' => $cart->book->title,
                    'book_price' => $cart->book->price,
                    'count' => $cart->count
                ];
            })->toJson();

            try {
                $transaction = Transaction::create([
                    'user_id' => $user->id,
                    'items' => $items,
                    'payment_proof' => $paymentProof,
                    'order_status' => false,
                    'confirmation' => false,
                    'total_price' => $totalPrice,
                ]);

                Cart::where('user_id', $user->id)->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Checkout successful, your order is being processed.',
                    'data' => $transaction,
                ], 201);
            } catch (\Exception $e) {
                Log::error('Error creating transaction: ' . $e->getMessage());
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to create transaction.',
                    'error' => $e->getMessage(),
                ], 500);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Checkout failed.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'tracking_number' => 'required|string|max:255',
            ]);

            $transaction = Transaction::findOrFail($id);
            $transaction->tracking_number = $request->tracking_number;
            $transaction->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Tracking number updated successfully!',
                'data' => $transaction,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update tracking number.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $transaction->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaction deleted successfully!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete transaction.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
