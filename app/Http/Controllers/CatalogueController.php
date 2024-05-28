<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        // Filter pencarian berdasarkan parameter yang diterima dari request
        if ($search = $request->input('search')) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('author', 'like', '%' . $search . '%')
                    ->orWhere('publication_year', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Mengambil hasil pencarian dengan pagination
        $books = $query->orderBy('title')->paginate(10)->withQueryString();

        return view('catalogue.index', compact('books'));
    }
}
