<?php

use Illuminate\Support\Facades\Route;
use Modules\DoctorManagement\App\Http\Controllers\Api\DoctorProfileController;
use Modules\DoctorManagement\App\Http\Controllers\Api\AvailabilityController;

Route::middleware('auth:sanctum')->group(function () {
    // Doctor profile routes
    Route::prefix('doctors')->group(function () {
        Route::get('/featured', [DoctorProfileController::class, 'featured']);
        Route::apiResource('/', DoctorProfileController::class);
    });

    // Doctor availability routes
    Route::prefix('availabilities')->group(function () {
        Route::get('/', [AvailabilityController::class, 'index']);
        Route::post('/', [AvailabilityController::class, 'store']);
        Route::put('/{availability}', [AvailabilityController::class, 'update']);
        Route::delete('/{availability}', [AvailabilityController::class, 'destroy']);
    });
});
