<?php

use Illuminate\Support\Facades\Route;
use Modules\UserManagement\App\Http\Controllers\Api\AuthController;
use Modules\UserManagement\App\Http\Controllers\Api\NotificationController;
use Modules\UserManagement\App\Http\Controllers\Api\VerificationController;
use Modules\UserManagement\App\Http\Controllers\Api\PasswordResetController;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [PasswordResetController::class, 'forgotPassword']);
    Route::post('verify-code', [PasswordResetController::class, 'verifyCode']);
    Route::post('reset-password', [PasswordResetController::class, 'setNewPassword']);
    Route::delete('user/{user}', [AuthController::class, 'destroy']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('verify-email', [VerificationController::class, 'verify'])
            ->name('verification.verify');
        Route::get('resend-verification-email', [VerificationController::class, 'resend'])
            ->name('verification.resend');
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('verify-token', [AuthController::class, 'verifyToken']);
    });
});

Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::get('notifications/read-all', [NotificationController::class, 'markAllAsRead']);
});
