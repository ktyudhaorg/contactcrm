<?php

use App\Http\Controllers\Api\V1\WhatsApp\WhatsAppController;
use App\Http\Controllers\Proxy\GoogleDrive\GoogleDriveController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/proxy', [GoogleDriveController::class, 'proxy'])->name('cloud.proxy');
    Route::get('/download', [GoogleDriveController::class, 'download'])->name('cloud.download');
});
