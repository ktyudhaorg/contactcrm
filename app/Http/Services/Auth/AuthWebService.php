<?php

namespace App\Http\Services\Auth;

use App\Http\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Auth;

class AuthWebService
{
    const AUTH_GUARD = 'web';

    public function __construct(protected UserRepository $userRepository) {}

    public function login(string $email, string $password)
    {
        if (! Auth::attempt(['email' => $email, 'password' => $password])) {
            throw new \Exception('Email atau password salah.');
        }

        session()->regenerate();
    }

    public function me()
    {
        if (Auth::guard(self::AUTH_GUARD)->check()) {
            return Auth::guard(self::AUTH_GUARD)->user();
        }
    }

    public function logout()
    {
        if (Auth::guard(self::AUTH_GUARD)->check()) {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
        }
    }
}
