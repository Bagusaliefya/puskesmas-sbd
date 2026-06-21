<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->error('Email atau password salah.', 401);
        }

        if (! $user->is_active) {
            return $this->error('Akun dinonaktifkan.', 403);
        }

        $token = $user->createToken('mobile')->plainTextToken;

        return $this->success([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ], 'Login berhasil.');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logout berhasil.');
    }

    public function user(Request $request): JsonResponse
    {
        return $this->success($request->user());
    }
}
