<?php

use Illuminate\Support\Facades\Route;
use Modules\DoctorManagement\App\Http\Controllers\Api\DoctorProfileController;
use Modules\DoctorManagement\App\Http\Controllers\Api\AvailabilityController;
use Modules\DoctorManagement\App\Http\Controllers\Api\DoctorOnboardingController;

Route::middleware('auth:sanctum')->group(function () {
    // Doctor profile routes
    Route::prefix('doctors')->name('api.doctors.')->controller(DoctorProfileController::class)->group(function () {
        Route::get('/statistics', 'statistics')->name('statistics');
        Route::get('/featured', 'featured')->name('featured');
        Route::get('/coverage-areas', 'getCoverageAreas')->name('coverage-areas');
        Route::get('/verify-status', 'verifyStatus')->name('verify-status');
        Route::get('/{doctor}', 'show')->name('show');
        Route::post('/profile', 'updateProfile')->name('profile.update');
        Route::post('/availabilities', 'updateAvailabilities')->name('availabilities.update');

        // Doctor onboarding routes
        Route::prefix('/onboarding')
            ->controller(DoctorOnboardingController::class)
            ->name('onboarding.')
            ->group(function () {
                Route::post('/basic', 'updateBasicInfo')->name('basic');
                Route::post('/medical', 'updateMedicalInfo')->name('medical');
                Route::post('/documents', 'uploadDocuments')->name('documents');
            });
    });
});
