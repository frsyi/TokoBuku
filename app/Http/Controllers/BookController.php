<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        // Ambil semua buku dengan relasi category dan urutkan berdasarkan created_at secara descending
        $books = Book::with('category')
            ->orderBy('created_at', 'desc')
            ->get();

        // Return view dengan data books
        return view('book.index', compact('books'));
    }

    public function create()
    {
        // Ambil semua kategori untuk form create
        $categories = Category::all();
        return view('book.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'publication_year' => 'required|max:4',
            'price' => 'required|numeric',
            'description' => 'required',
            'image' => 'required|image|max:2048',
        ]);

        // Buat buku baru
        $book = new Book($request->all());

        if ($request->hasFile('image')) {
            $book->image = $request->file('image')->store('images', 'public');
        }

        $book->save();

        return redirect()->route('book.index')->with('success', 'Book created successfully!');
    }
}
