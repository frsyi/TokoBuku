<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carts = Cart::where('user_id', Auth::user()->id)->get();
        return view('cart.index', compact('carts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cart.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'count' => 'required|integer|min:1',
        ]);

        $book = Book::findOrFail($id);

        $cart = Cart::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->first();

        if ($cart) {
            // Update existing cart item if it already exists
            $cart->count += $request->count;
            $cart->save();
        } else {
            // Create new cart item
            Cart::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'count' => $request->count,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item added to cart successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::where('id', $id)->first();
        return view('cart.create', compact('book'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cart = Cart::findOrFail($id);

        // Hapus item dari keranjang
        $cart->delete();

        return redirect()->route('cart.index')->with('success', 'Order deleted successfully!');
    }
}
