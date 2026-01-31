<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => 'throttle:api'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', LoginController::class)
            ->middleware('throttle:login');
        Route::post('/register', RegisterController::class)
            ->middleware('throttle:register');
    });

    Route::group(['prefix' => 'admin'], function () {
        Route::apiResource('/users', UserController::class)
            ->middleware('auth:sanctum');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::patch('{uuid}/update-password', [UserController::class, 'updatePassword'])
            ->middleware('auth:sanctum');
    });
});
