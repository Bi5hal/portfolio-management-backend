<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $login = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        if (!Auth::attempt($login)) {
            return response()->json([
                'type' => 'error',
                'message' => 'Invalid login credentials.'
            ], 401);
        }
        $accessToken = $request->user()->createToken('authToken')->plainTextToken;

        $user = User::where('id', Auth::id())->first();
        return response()->json([
            'type' => 'success',
            'message' => 'Login Successful',
            'data' => [
                'user' => $user,
                'accessToken' => $accessToken,
            ]
        ]);
    }

    public function register(Request $request)
    {
        $register = $request->validate([
            'name' => 'required|string',
            'email' => 'email|required|string|unique:users',
            'phoneNo' => 'required',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $register['password'] = Hash::make($register['password']);

        if (!User::create($register)) {
            return response()->json([
                'type' => 'error',
                'message' => 'User registration not successful.'
            ], 401);
        }

        return response()->json([
            'type' => 'success',
            'message' => 'User registration successful.'
        ]);

    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json([
            'type' => 'success',
            'message' => 'User logout successful.'
        ], 200);
    }
}
