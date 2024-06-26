<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('books')->get();
        return response()->json([
            'status' => 'success',
            'data' => [
                'categories' => $categories,
            ]
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user = auth()->user();

            // Validasi hanya untuk pengguna dengan ID 1
            if ($user->id !== 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are not authorized to perform this action.',
                ], 403);
            }

            $request->validate([
                'name' => 'required|max:255',
            ]);

            $category = Category::create([
                'name' => ucfirst($request->name),
                'user_id' => $user->id,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Category created',
                'data' => [
                    'category' => $category,
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
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category not found.',
                ], 404);
            }

            $request->validate([
                'name' => 'required|max:255',
            ]);

            $category->update([
                'name' => ucfirst($request->name),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully.',
                'data' => [
                    'category' => $category,
                ]
            ]);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $exception->errors(),
            ], 422);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update category. Please try again later.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = auth()->user();

            // Validasi hanya untuk pengguna dengan ID 1
            if ($user->id !== 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are not authorized to perform this action.',
                ], 403);
            }

            // Ambil kategori berdasarkan ID tanpa memeriksa 'user_id'
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category not found.',
                ], 404);
            }

            // Hapus kategori
            $category->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted successfully.',
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete category. Please try again later.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }
}
