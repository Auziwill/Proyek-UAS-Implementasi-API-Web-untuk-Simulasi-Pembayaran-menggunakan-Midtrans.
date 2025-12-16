<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * REGISTER → LANGSUNG DAPAT TOKEN
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        // 1. Buat user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // 2. Generate token Passport
        $tokenResult = $user->createToken('API Token');

        return response()->json([
            'message' => 'Register berhasil',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $tokenResult->accessToken
        ], 201);
    }

    /**
     * LOGIN → TOKEN BARU
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        $tokenResult = $user->createToken('API Token');

        return response()->json([
            'message' => 'Login berhasil',
            'token_type' => 'Bearer',
            'access_token' => $tokenResult->accessToken
        ]);
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
}
