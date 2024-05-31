<?php

use App\Http\Controllers\Api\V1\Auth\AuthenticatedApiController;
use App\Http\Controllers\Api\V1\GroupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'name' => 'v1'], function () {
    Route::group(['prefix' => 'auth', 'name' => 'auth'], function () {
        Route::post('/login', [AuthenticatedApiController::class, 'login'])
            ->middleware('guest')
            ->name('login');

        Route::post('/register', [AuthenticatedApiController::class, 'register'])
            ->middleware('guest')
            ->name('register');
    });

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::apiResource('groups', GroupController::class);
    });
});
