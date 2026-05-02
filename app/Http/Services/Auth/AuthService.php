<?php

namespace App\Http\Services\Auth;

use App\Http\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    const AUTH_GUARD = 'api';
    const TOKEN = 'apiNextCrmToken';

    public function __construct(protected UserRepository $userRepository) {}

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'     => ['required', 'email'],
            'password'  => ['required'],
        ]);

        $user = $this->userRepository->findUserAuth($validated['email']);

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated',
            ], 401);
        }

        $user->tokens()->delete();
        $token = $user->createToken(self::TOKEN);

        return response()->json([
            'status' => 'Success',
            'message' => 'Authenticated',
            'data' => $token->plainTextToken,
        ], 200);
    }

    public function me()
    {
        if (Auth::guard(self::AUTH_GUARD)->check()) {
            return Auth::guard(self::AUTH_GUARD)->user();
        }

        return 'tes';
    }

    public function logout()
    {
        if (Auth::guard(self::AUTH_GUARD)->check()) {
            $user = Auth::guard(self::AUTH_GUARD)->user();
            $user->currentAccessToken()->delete();
        }
    }
}
