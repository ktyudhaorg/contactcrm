<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Auth\AuthService;

class AuthController extends Controller
{

    public function __construct(protected AuthService $authService) {}

    public function login(Request $request)
    {
        return $this->authService->login($request);
    }

    public function me()
    {
        $data = $this->authService->me();

        return response()->json([
            'status' => 'success',
            'message' => 'Authenticated',
            'data' => $data
        ]);
    }

    public function logout()
    {
        $this->authService->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
