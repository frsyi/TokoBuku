<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
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
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publication_year' => 'required|integer',
            'price' => 'required|integer',
            'description' => 'required|string',
            'category_id' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->publication_year = $request->publication_year;
        $book->price = $request->price;
        $book->description = $request->description;
        $book->category_id = $request->category_id;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            Log::info('File is valid and present.');

            try {
                $file = $request->file('image');
                $fileName = time().'_'.$file->getClientOriginalName();
                $destinationPath = public_path('storage/images');
                $file->move($destinationPath, $fileName);
                Log::info('File moved to: ' . $destinationPath . '/' . $fileName);
                $book->image = 'images/'.$fileName;
            } catch (\Exception $e) {
                Log::error('File storage error: ' . $e->getMessage());
                return back()->withErrors(['image' => 'File storage error: ' . $e->getMessage()]);
            }
        } else {
            Log::error('File is not valid or not present.');
            return back()->withErrors(['image' => 'Invalid file upload.']);
        }

        $book->save();

        return redirect()->route('book.index')->with('success', 'Book created successfully.');
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
