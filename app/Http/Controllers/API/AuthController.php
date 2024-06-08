<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (auth()->attempt($credentials)) {
            $user = $request->user();
            if ($user->tokens) {
                $user->tokens()->delete();
            }
            $token = $user->createToken('Personal Access Token')->plainTextToken;
            return response()->json([
                'status' => 'Login success!',
                'token_type' => 'Bearer',
                'access_token' => $token
            ], 201);
        } else {
            return response()->json([
                'status' => 'Login fail!'
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'status' => 'Logout success!'
        ], 200);
    }


    public function register(Request $request)
    {
        // Validasi data yang diterima dari request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Buat pengguna baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Buat token akses personal untuk pengguna
        $token = $user->createToken('Personal Access Token')->plainTextToken;

        return response()->json([
            'status' => 'Registration success!',
            'token_type' => 'Bearer',
            'access_token' => $token,
            'user' => $user
        ], 201);
    }

}
