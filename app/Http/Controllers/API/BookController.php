<?php

namespace App\Http\Controllers\API;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('category')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'books' => $books,
            ]
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'publication_year' => 'required|integer',
                'price' => 'required|integer',
                'description' => 'required|string',
                'category_id' => 'required|integer|exists:categories,id',
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
                try {
                    $file = $request->file('image');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $destinationPath = public_path('storage/images');
                    $file->move($destinationPath, $fileName);
                    $book->image = 'images/' . $fileName;
                } catch (\Exception $e) {
                    Log::error('File storage error: ' . $e->getMessage());
                    return response()->json(['error' => 'File storage error: ' . $e->getMessage()], 500);
                }
            } else {
                return response()->json(['error' => 'Invalid file upload.'], 400);
            }

            $book->save();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'book' => $book,
                ]
            ], 201);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $exception->errors(),
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $book = Book::with('category')->find($id);

        if (!$book) {
            return response()->json([
                'status' => 'forbidden',
                'message' => 'Book not found'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'book' => $book,
            ]
        ], 200);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        try {
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
                if ($book->image) {
                    Storage::delete($book->image);
                }
                $file = $request->file('image');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('images', $fileName, 'public');
                $book->image = $filePath;
            }

            $book->save();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'book' => $book,
                ]
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $exception->errors(),
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        if ($book->image) {
            Storage::delete($book->image);
        }
        $book->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Book deleted successfully!',
        ], 200);
    }
}
