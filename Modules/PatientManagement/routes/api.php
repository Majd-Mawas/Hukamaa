<?php

use Illuminate\Support\Facades\Route;
use Modules\PatientManagement\App\Http\Controllers\Api\ChatController;
use Modules\PatientManagement\App\Http\Controllers\Api\PatientOnboardingController;
use Modules\PatientManagement\App\Http\Controllers\Api\PatientProfileController;

Route::middleware(['auth:sanctum'])
    ->prefix('patient')->group(function () {
        Route::prefix('/onboarding')->group(function () {
            Route::post('/basic', [PatientOnboardingController::class, 'updateBasicInfo']);
            Route::post('/extra', [PatientOnboardingController::class, 'updateExtraInfo']);
        });
        Route::get('/allergies', [PatientProfileController::class, 'getAllergies'])->name('allergies');
        Route::get('/chronic-conditions', [PatientProfileController::class, 'getChronicConditions'])->name('chronic-conditions');
        Route::post('/update-profile', [PatientProfileController::class, 'update']);
    });

Route::middleware(['auth:sanctum'])
    ->prefix('chat')->group(function () {
        Route::get('/{appointment}', [ChatController::class, 'getChat']);
        // Route::get('/{receiver}', [ChatController::class, 'getChatMessages']);
        Route::post('/start/{appointment}', [ChatController::class, 'startChat']);
        Route::post('/send/{appointment}', [ChatController::class, 'sendMessage']);
        Route::post('/{sender}/read', [ChatController::class, 'markAsRead']);
    });
