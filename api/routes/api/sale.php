<?php

use App\Http\Controllers\Sale\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/sales',            [OrderController::class, 'listing']);
Route::delete('/sales/{id}',    [OrderController::class, 'delete']);
