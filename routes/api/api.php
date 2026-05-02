<?php

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'api.'], function () {
    // import api v1
    require __DIR__ . '/v1/api.php';
});
