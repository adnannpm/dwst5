<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse {
        $data = $request->validate([
            'full_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ],[
            'full_name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'The email has already been taken.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password field must be at least 6 characters.'
        ]);

        $user = User::create([
            'name' => $data['full_name'],
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        $user->token = $token;

        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful',
            'data' => $user,
        ], 201);
    }

    public function login(Request $request): JsonResponse {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if(Auth::attempt($credentials)){
            $user = Auth::user();

            $token = $user->createToken('auth_token')->plainTextToken;
            $user->token = $token;

            }
        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => $user,
        ]);
    }

    public function logout(Request $request): JsonResponse {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout successful'
        ]);
    }
}
