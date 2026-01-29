<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => 'throttle:api'], function () {

    Route::get('/user', function (Request $request) {
        return $request->user() ?? 'No user logged in.';
    })->middleware('auth:sanctum');

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', \App\Http\Controllers\Auth\LoginController::class)
            ->middleware('throttle:login');
        Route::post('/register', \App\Http\Controllers\Auth\RegisterController::class)
            ->middleware('throttle:register');
    });
});
