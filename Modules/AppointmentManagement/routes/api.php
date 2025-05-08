<?php

use Illuminate\Support\Facades\Route;
use Modules\AppointmentManagement\Http\Controllers\Api\AppointmentController;
use Modules\AppointmentManagement\Http\Controllers\Api\VideoCallController;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('appointments')->group(function () {
        Route::get('/', [AppointmentController::class, 'index']);
        Route::post('/', [AppointmentController::class, 'store']);
        Route::get('/{appointment}', [AppointmentController::class, 'show']);
        Route::put('/{appointment}', [AppointmentController::class, 'update']);
        Route::delete('/{appointment}', [AppointmentController::class, 'destroy']);
        Route::post('/{appointment}/confirm', [AppointmentController::class, 'confirm']);
    });

    Route::prefix('video-calls')->group(function () {
        Route::post('/', [VideoCallController::class, 'store']);
        Route::put('/{videoCall}', [VideoCallController::class, 'update']);
        Route::post('/{videoCall}/end', [VideoCallController::class, 'end']);
    });
});
