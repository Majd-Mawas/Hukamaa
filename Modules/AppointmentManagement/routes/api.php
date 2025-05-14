<?php

use Illuminate\Support\Facades\Route;
use Modules\AppointmentManagement\App\Http\Controllers\Api\AppointmentController;
use Modules\AppointmentManagement\App\Http\Controllers\Api\VideoCallController;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('appointments')->group(function () {
        Route::apiResource('/', AppointmentController::class);
        Route::post('/{appointment}/cancel', [AppointmentController::class, 'cancel']);
        Route::get('/available-slots', [AppointmentController::class, 'getAvailableSlots']);
        Route::prefix('/{appointment}/confirm')->group(function () {
            Route::post('datetime', [AppointmentController::class, 'confirmDateTime']);
            Route::post('payment', [AppointmentController::class, 'confirmPayment']);
        });
    });

    Route::prefix('video-calls')->group(function () {
        Route::post('/', [VideoCallController::class, 'store']);
        Route::put('/{videoCall}', [VideoCallController::class, 'update']);
        Route::post('/{videoCall}/end', [VideoCallController::class, 'end']);
    });
});
