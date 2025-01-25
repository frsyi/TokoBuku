<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Get the authenticated user's profile.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        return response()->json([
            'status' => 'success',
            'data' => $user,
        ], 200);
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(Request $request)
    {
        try {
            $user = $request->user();

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'phone_number' => 'nullable|string|max:15',
                'address' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user->fill($request->only(['name', 'email', 'phone_number', 'address']));

            // Jika email diubah, atur ulang verifikasi email
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            return response()->json([
                'status' => 'success',
                'data' => $user,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update profile. Please try again later.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete the authenticated user's account.
     */
    public function destroy(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password' => ['required', 'current_password'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = $request->user();

            $user->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Account deleted successfully.',
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete profile. Please try again later.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }
}
