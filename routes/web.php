<?php

use App\Livewire\Ai\Ai;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\WhatsApp\Chat;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'web.'], function () {
    // import api v1

    /** Auth */
    Route::livewire('/login', Login::class)->name('login');

    /** App */
    Route::middleware('auth')->group(function () {
        // Route::livewire('/', 'pages::users.index')->name('home');

        Route::get('/ai', Ai::class)->name('ai');
        Route::get('/dashboard', Dashboard::class)->name('home');

        Route::prefix('whatsapp')->group(function () {
            Route::get('/chats', Chat::class)->name('chats');
        });
    });
});
