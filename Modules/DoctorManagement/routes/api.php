<?php

use Illuminate\Support\Facades\Route;
use Modules\DoctorManagement\App\Http\Controllers\Api\DoctorProfileController;
use Modules\DoctorManagement\App\Http\Controllers\Api\AvailabilityController;

Route::middleware('auth:sanctum')->group(function () {
    // Doctor profile routes
    Route::prefix('doctors')->name('doctors.')->group(function () {
        Route::get('/featured', [DoctorProfileController::class, 'featured'])->name('featured');
        Route::get('/', [DoctorProfileController::class, 'index'])->name('index');
        Route::post('/', [DoctorProfileController::class, 'store'])->name('store');
        Route::get('/{doctor}', [DoctorProfileController::class, 'show'])->name('show');
        Route::put('/{doctor}', [DoctorProfileController::class, 'update'])->name('update');
        Route::delete('/{doctor}', [DoctorProfileController::class, 'destroy'])->name('destroy');
    });

    // Doctor availability routes
    Route::prefix('availabilities')->name('availabilities.')->group(function () {
        Route::get('/', [AvailabilityController::class, 'index'])->name('index');
        Route::post('/', [AvailabilityController::class, 'store'])->name('store');
        Route::put('/{availability}', [AvailabilityController::class, 'update'])->name('update');
        Route::delete('/{availability}', [AvailabilityController::class, 'destroy'])->name('destroy');
    });
});
