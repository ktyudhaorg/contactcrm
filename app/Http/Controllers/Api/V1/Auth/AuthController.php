<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Services\Auth\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $data = $this->authService->login($validated['email'], $validated['password']);

        return response()->json([
            'status' => 'Success',
            'message' => 'Authenticated',
            'data' => $data,
        ]);
    }

    public function me()
    {
        $data = $this->authService->me();

        return response()->json([
            'status' => 'success',
            'message' => 'Authenticated',
            'data' => $data,
        ]);
    }

    public function logout()
    {
        $this->authService->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
