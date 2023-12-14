<?php

use App\Http\Controllers\POS\POSController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'pos'], function () {
    Route::get('/products', [POSController::class, 'getProducts']);
    Route::post('/order',   [POSController::class, 'makeOrder']);
});