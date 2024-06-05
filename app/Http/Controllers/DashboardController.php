<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Di dalam method dashboard Controller
public function dashboard()
{
    $related_books = Book::inRandomOrder()->get();
    return view('dashboard', compact('related_books'));
}

}
