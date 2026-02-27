<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => 'throttle:api'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', LoginController::class)
            ->middleware('throttle:login');

        Route::post('/register', RegisterController::class)
            ->middleware('throttle:register');
    });

    Route::group(['prefix' => 'admin'], function () {
        Route::apiResource('/users', \App\Http\Controllers\Admin\UserController::class)
            ->middleware('auth:sanctum')
            ->names('admin.users');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::patch('/update-password', [\App\Http\Controllers\Users\UserController::class, 'updatePassword'])
            ->middleware('auth:sanctum')
            ->name('users.update-password');

        Route::apiResource('/', \App\Http\Controllers\Users\UserController::class)
            ->middleware('auth:sanctum')
            ->only(['show', 'update'])
            ->names('users')
            ->parameters(['' => 'user']); // Force the parameter name to 'user' even on root resource

        Route::post('/follow', [\App\Http\Controllers\Users\UserController::class, 'followUser'])
            ->middleware('auth:sanctum')
            ->name('users.follow');

        Route::post('/unfollow', [\App\Http\Controllers\Users\UserController::class, 'unfollowUser'])
            ->middleware('auth:sanctum')
            ->name('users.unfollow');
    });

    Route::group(['prefix' => 'posts'], function () {
        Route::apiResource("/", \App\Http\Controllers\Posts\PostController::class)
            ->middleware('auth:sanctum')
            ->names('posts')
            ->parameters(['' => 'post']);
    });

    // PUBLIC ACCESS
    Route::group(['prefix' => 'rest'], function () {
        Route::get('/users/{user}', [\App\Http\Controllers\Rest\UserController::class, 'show'])
            ->name('rest.users.user.show');

        Route::get("/posts", [\App\Http\Controllers\Rest\PostController::class, "index"])
            ->name('rest.posts');

        Route::get('/posts/{post}', [\App\Http\Controllers\Rest\PostController::class, 'show'])
            ->name('rest.posts.show');
    });
});
