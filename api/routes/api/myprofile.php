<?php

use App\Http\Controllers\MyProfile\MyProfileController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'my-profiles'], function () {
    Route::get('/',                 [MyProfileController::class, 'view']);
    Route::post('/',                [MyProfileController::class, 'update']);
    Route::post('/change-password', [MyProfileController::class, 'changePassword']);
});
