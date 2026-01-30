<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => 'throttle:api'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', \App\Http\Controllers\Auth\LoginController::class)
            ->middleware('throttle:login');
        Route::post('/register', \App\Http\Controllers\Auth\RegisterController::class)
            ->middleware('throttle:register');
    });

    Route::group(['prefix' => 'admin'], function () {
        Route::apiResource('/users', \App\Http\Controllers\UserController::class)
            ->middleware('auth:sanctum');
    });
});
