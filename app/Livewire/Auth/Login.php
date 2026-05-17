<?php

namespace App\Livewire\Auth;

use App\Http\Services\Auth\AuthWebService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

#[Layout('layouts.auth')]
#[Title('Login')]

class Login extends Component
{
    use Toast;

    public string $email = 'superadmin@nextcrm.com';

    public string $password = 'password';

    protected AuthWebService $authWebService;

    public function boot(AuthWebService $authWebService): void
    {
        $this->authWebService = $authWebService;

    }

    public function mount(): void
    {
        if (Auth::check()) {
            $this->redirect(route('web.home'), navigate: true);
        }
    }

    public function login(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        try {
            $this->authWebService->login($this->email, $this->password);
            $this->redirect(route('web.home'), navigate: true);

        } catch (\Exception $e) {
            $this->error($e->getMessage(), position: 'toast-top');
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
