<?php

    use Illuminate\Support\Facades\Route;

    /*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
    */


    
    //============>>  Auth
    Route::group(['prefix' => 'auth'], function () {
        require(__DIR__ . '/api/auth.php');
    });

    //============>>  Authenticated
    Route::group(['middleware' => ['jwt.verify']], function () {

        //============>>  Dashboard
        require(__DIR__ . '/api/dashboard.php');

        //============>>  POS
        require(__DIR__ . '/api/pos.php');

        //============>>  Sale
        require(__DIR__ . '/api/sale.php');

        //============>>  Product
        require(__DIR__ . '/api/product.php');

        //============>>  User
        require(__DIR__ . '/api/user.php');

        //============>> My Profile
        require(__DIR__ . '/api/myprofile.php');
    });
