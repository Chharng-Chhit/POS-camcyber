<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ProductTypeController;

Route::get('/products',         [ProductController::class, 'listing']); // Read Many Records
Route::get('/products/{id}',    [ProductController::class, 'view']); // Read 1 Record
Route::post('/products',        [ProductController::class, 'create']);
Route::post('/products/{id}',   [ProductController::class, 'update']); // Update
Route::delete('/products/{id}', [ProductController::class, 'delete']);

Route::group(['prefix' => 'product'], function () {
    Route::get('/types',            [ProductTypeController::class, 'listing']);
    Route::post('/types',           [ProductTypeController::class, 'create']);
    Route::post('/types/{id}',      [ProductTypeController::class, 'update']);
    Route::delete('/types/{id}',    [ProductTypeController::class, 'delete']);
});
