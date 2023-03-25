<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SocialMediaController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('login', LoginController::class)->name('login');
    Route::post('users', [UserController::class, 'store'])->name('users.store');

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('social_medias', SocialMediaController::class);
        Route::apiResource('profiles', ProfileController::class);
        Route::apiResource('users', UserController::class)->except('store');

        Route::group(['prefix' => 'organizations', 'as' => 'organizations.'], function() {
            Route::post('users', [OrganizationController::class, 'storeUser'])->name('users.store');
        });
    });
})->name('api');

