<?php

use App\Http\Controllers\Api\V1\Ai\EklinikController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\WhatsApp\WhatsAppController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'as' => 'v1.'], function () {

    /** AUTHENTICATION */
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login');
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('throttle:10,1')->name('logout');
    });

    /** MIDDLEWARE SANCTUM*/
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me'])->name('auth.me');

        /** WHATSAPP */
        Route::prefix('whatsapp')->name('whatsapp.')->group(function () {
            Route::get('/chats', [WhatsAppController::class, 'chats'])->name('chats');
            Route::post('/send-message', [WhatsAppController::class, 'sendMessage'])->name('send-message');
        });

        Route::prefix('ai')->name('ai.')->group(function () {
            Route::prefix('eklinik')->name('eklinik.')->group(function () {
                Route::post('/prompt', [EklinikController::class, 'prompt'])->name('prompt');
            });
        });
    });

    /** MIDDLEWARE HMAC */
    Route::middleware('hmac')->group(function () {
        Route::post('/whatsapp/webhook', [WhatsAppController::class, 'webhook'])->name('webhook');
    });

});
