<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $related_books = Book::where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('author', 'like', '%' . $search . '%')
                    ->orWhere('publication_year', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            })
                ->orderBy('title')
                ->paginate(10)
                ->withQueryString();
        } else {
            $related_books = Book::inRandomOrder()->get();
        }

        return view('dashboard', compact('related_books'));
    }
}
