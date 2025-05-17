<?php

use Illuminate\Support\Facades\Route;
use Modules\PatientManagement\App\Http\Controllers\Api\PatientOnboardingController;
use Modules\PatientManagement\App\Http\Controllers\Api\PatientProfileController;

Route::middleware(['auth:sanctum', 'patient'])->prefix('patient')->group(function () {
    Route::prefix('/onboarding')->group(function () {
        Route::post('/basic', [PatientOnboardingController::class, 'updateBasicInfo']);
        Route::post('/extra', [PatientOnboardingController::class, 'updateExtraInfo']);
    });
    Route::post('/update-profile', [PatientProfileController::class, 'update']);
});
