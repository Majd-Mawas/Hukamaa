<?php

use Illuminate\Support\Facades\Route;
use Modules\DoctorManagement\App\Http\Controllers\Api\DoctorProfileController;
use Modules\DoctorManagement\App\Http\Controllers\Api\AvailabilityController;

Route::middleware('auth:sanctum')->group(function () {
    // Doctor profile routes
    Route::prefix('doctors')->group(function () {
        Route::get('/', [DoctorProfileController::class, 'index']);
        Route::post('/', [DoctorProfileController::class, 'store']);
        Route::get('/{doctorProfile}', [DoctorProfileController::class, 'show']);
        Route::put('/{doctorProfile}', [DoctorProfileController::class, 'update']);
        Route::delete('/{doctorProfile}', [DoctorProfileController::class, 'destroy']);
    });

    // Doctor availability routes
    Route::prefix('availabilities')->group(function () {
        Route::get('/', [AvailabilityController::class, 'index']);
        Route::post('/', [AvailabilityController::class, 'store']);
        Route::put('/{availability}', [AvailabilityController::class, 'update']);
        Route::delete('/{availability}', [AvailabilityController::class, 'destroy']);
    });
});
