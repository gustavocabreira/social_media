<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::post('login', LoginController::class)->name('login');
    Route::post('users', [UserController::class, 'store'])->name('users.store');

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('users', UserController::class)->except('store');
    });
})->name('api');

