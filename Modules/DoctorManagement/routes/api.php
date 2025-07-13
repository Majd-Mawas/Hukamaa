<?php

use Illuminate\Support\Facades\Route;
use Modules\DoctorManagement\App\Http\Controllers\Api\DoctorProfileController;
use Modules\DoctorManagement\App\Http\Controllers\Api\AvailabilityController;
use Modules\DoctorManagement\App\Http\Controllers\Api\DoctorOnboardingController;

Route::middleware('auth:sanctum')->group(function () {
    // Doctor profile routes
    Route::prefix('doctors')->name('api.doctors.')->group(function () {
        Route::get('/statistics', [DoctorProfileController::class, 'statistics'])->name('statistics');
        Route::get('/featured', [DoctorProfileController::class, 'featured'])->name('featured');
        Route::get('/coverage-areas', [DoctorProfileController::class, 'getCoverageAreas'])->name('coverage-areas');
        Route::get('/verify-status', [DoctorProfileController::class, 'verifyStatus'])->name('verify-status');
        Route::get('/{doctor}', [DoctorProfileController::class, 'show'])->name('show');
        Route::post('/profile', [DoctorProfileController::class, 'updateProfile'])->name('profile.update');
        Route::post('/availabilities', [DoctorProfileController::class, 'updateAvailabilities'])->name('availabilities.update');

        // Doctor onboarding routes
        Route::prefix('/onboarding')
            ->name('onboarding.')
            ->group(function () {
                Route::post('/basic', [DoctorOnboardingController::class, 'updateBasicInfo']);
                Route::post('/medical', [DoctorOnboardingController::class, 'updateMedicalInfo']);
                Route::post('/documents', [DoctorOnboardingController::class, 'uploadDocuments']);
            });
    });
});
