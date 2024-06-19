<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        $related_books = Book::when($request->search, function ($query) use ($request) {
            return $query->where('title', 'like', '%' . $request->search . '%')
                         ->orWhere('author', 'like', '%' . $request->search . '%')
                         ->orWhere('publication_year', 'like', '%' . $request->search . '%')
                         ->orWhereHas('category', function ($query) use ($request) {
                             return $query->where('name', 'like', '%' . $request->search . '%');
                         });
        })->paginate(10);

        return view('welcome', compact('related_books'));
    }
}
