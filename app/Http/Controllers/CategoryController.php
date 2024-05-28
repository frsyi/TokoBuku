<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('books')->get();

        return view('category.index', compact('categories'));
    }

    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|max:255',
        ]);

        // Membuat kategori baru dengan menambahkan user_id dari pengguna yang sedang login
        Category::create([
            'name' => ucfirst($request->name),
        ]);

        // Redirect ke halaman index kategori dengan pesan sukses
        return redirect()->route('category.index')->with('success', 'Category created successfully!');
    }

    public function edit(Category $category)
    {
        // Memeriksa apakah pengguna yang masuk adalah admin
        if (auth()->user()->is_admin) {
            return view('category.edit', compact('category'));
        } else {
            // Jika bukan admin, redirect ke halaman index kategori dengan pesan akses ditolak
            return redirect()->route('category.index')->with('danger', 'You are not authorized to edit this category!');
        }
    }

    public function update(Request $request, Category $category)
    {
        // Memeriksa apakah pengguna yang masuk adalah admin
        if (auth()->user()->is_admin) {
            $request->validate([
                'name' => 'required|max:255',
            ]);

            $category->update([
                'name' => ucfirst($request->name),
            ]);

            return redirect()->route('category.index')->with('success', 'Category updated successfully!');
        } else {
            // Jika bukan admin, redirect ke halaman index kategori dengan pesan akses ditolak
            return redirect()->route('category.index')->with('danger', 'You are not authorized to update this category!');
        }
    }

    public function destroy(Category $category)
    {
        // Memeriksa apakah pengguna yang masuk adalah admin
        if (auth()->user()->is_admin) {
            $category->delete();
            return redirect()->route('category.index')->with('success', 'Category deleted successfully!');
        } else {
            // Jika bukan admin, redirect ke halaman index kategori dengan pesan akses ditolak
            return redirect()->route('category.index')->with('danger', 'You are not authorized to delete this category!');
        }
    }
}
