<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeController;


Route::namespace('api')->as('api.')->group(function () {
        //Testing 
        Route::get('home', [HomeController::class, 'index']);

        Route::middleware(['jwt.verify'])->group(function () {
            Route::post('login', [AuthController::class, 'login'])->name('login');
            Route::post('signup', [AuthController::class, 'signup'])->name('signup');
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');
            Route::post('change-password', [AuthController::class, 'changePassword'])->name('change.password');
            Route::post('forget-password', [AuthController::class, 'forgetPassword'])->name('forget.password');
            Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('reset.password');
        });

        Route::group(['prefix' => 'auth'], function () {});
});