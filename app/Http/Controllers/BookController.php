<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Book;
use App\Models\Category;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('category')->orderBy('created_at', 'desc')->get();
        return view('book.index', compact('books'));
    }

    public function show(Book $book)
    {
        return view('book.detail', compact('book'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('book.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'publication_year' => 'required|max:4',
            'price' => 'required|numeric',
            'description' => 'required',
            'image' => 'required|image|file|max:2048',
        ]);

        $book = new Book($request->all());

        if ($request->hasFile('image')) {
            $book->image = $request->file('image')->store('images', 'public');
        }

        $book->save();

        return redirect()->route('book.index')->with('success', 'Book created successfully!');
    }

    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('book.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'publication_year' => 'required|max:4',
            'price' => 'required|numeric',
            'description' => 'required',
            'image' => 'nullable|image|file|max:2048',
        ]);

        $book->update($request->all());

        if ($request->hasFile('image')) {
            if ($request->oldImage) {
                Storage::delete($request->oldImage);
            }
            $book->image = $request->file('image')->store('images', 'public');
            $book->save();
        }

        return redirect()->route('book.index')->with('success', 'Book updated successfully!');
    }

    public function destroy(Book $book)
    {
        if ($book->image) {
            Storage::delete($book->image);
        }
        $book->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('book.index')->with('success', 'Book deleted successfully!');
    }
}
