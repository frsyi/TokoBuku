<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
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
