<?php

namespace App\Http\Controllers\API;

use App\Models\Book;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $carts = Cart::where('user_id', $user->id)->with('book')->get();

            return response()->json([
                'status' => 'success',
                'data' => $carts,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve cart items.',
                'error' => $exception->getMessage(),
            ], 500);
        }
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
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            $request->validate([
                'book_id' => 'required|exists:books,id',
                'count' => 'required|integer|min:1',
            ]);

            $book = Book::findOrFail($request->book_id);

            $cart = Cart::where('user_id', $user->id)
                ->where('book_id', $book->id)
                ->first();

            if ($cart) {
                // Update existing cart item if it already exists
                $cart->count += $request->count;
                $cart->save();
            } else {
                // Create new cart item
                Cart::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'count' => $request->count,
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Item added to cart successfully.',
            ], 201);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $exception->errors(),
            ], 422);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to add item to cart.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $cart = Cart::with('book')->where('id', $id)->first();

            if (!$cart) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cart item not found.',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $cart,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve cart item.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $cart = Cart::findOrFail($id);

            // Hapus item dari keranjang
            $cart->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Item deleted from cart successfully.',
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete cart item.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }
}
