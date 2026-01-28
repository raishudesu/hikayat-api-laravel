<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {

    Route::get('/user', function (Request $request) {
        return $request->user() ?? 'No user logged in.';
    })->middleware('auth:sanctum');

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', \App\Http\Controllers\Auth\LoginController::class);
        Route::post('/register', \App\Http\Controllers\Auth\RegisterController::class);
    });
});
