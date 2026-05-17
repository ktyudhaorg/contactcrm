<?php

namespace App\Livewire\Auth;

use App\Http\Services\Auth\AuthWebService;
use Livewire\Component;

class Logout extends Component
{
    protected AuthWebService $authWebService;

    public function boot(AuthWebService $authWebService): void
    {
        $this->authWebService = $authWebService;
    }

    public function logout()
    {
        $this->authWebService->logout();
        $this->redirect(route('web.login'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.logout');
    }
}
