<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function show($id)
    {
        $book = Book::where('id', $id)->first();
        return view('order.index', compact('book'));
    }
}
