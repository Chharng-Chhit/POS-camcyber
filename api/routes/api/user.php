<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/users', 						[UserController::class, 'listing']);
Route::get('/users/{id}', 					[UserController::class, 'view']);
Route::post('/users', 						[UserController::class, 'create']);
Route::post('/users/{id}', 					[UserController::class, 'update']);
Route::post('/users/block/{id}', 			[UserController::class, 'block']);
Route::delete('/users/{id}', 				[UserController::class, 'delete']);
Route::post('/users/{id}/change-password',  [UserController::class, 'changePassword']);
Route::group(['prefix' => 'user'],function (){
    Route::get('/get-type',               [UserController::class, 'getType']);
});