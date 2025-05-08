<?php

use Illuminate\Support\Facades\Route;
use Modules\PatientManagement\Http\Controllers\Api\PatientOnboardingController;

Route::middleware(['auth:sanctum', 'role:patient'])->prefix('patient/onboarding')->group(function () {
    Route::post('basic', [PatientOnboardingController::class, 'updateBasicInfo']);
    Route::post('extra', [PatientOnboardingController::class, 'updateExtraInfo']);
});
