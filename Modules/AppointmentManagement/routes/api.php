<?php

use Illuminate\Support\Facades\Route;
use Modules\AppointmentManagement\App\Http\Controllers\Api\AppointmentController;
use Modules\AppointmentManagement\App\Http\Controllers\Api\HomeVisitController;
use Modules\AppointmentManagement\App\Http\Controllers\Api\VideoCallController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('appointments')
        ->name('appointments.')
        ->group(function () {

            Route::post('/', [AppointmentController::class, 'confirmDateTime'])->name('store');
            // Route::post('/datetime', [AppointmentController::class, 'confirmDateTime']);
            Route::get('/available-slots', [AppointmentController::class, 'getAvailableSlots']);
            Route::get('/upcoming', [AppointmentController::class, 'getUpcomingAppointments']);
            Route::get('/done', [AppointmentController::class, 'getDoneAppointments']);
            Route::post('/{appointment}', [AppointmentController::class, 'update'])->name('update');
            Route::post('/{appointment}/cancel', [AppointmentController::class, 'cancel']);
            Route::prefix('/{appointment}/confirm')->group(function () {
                Route::post('payment', [AppointmentController::class, 'confirmPayment']);
            });
            Route::prefix('doctor')
                ->group(function () {
                    Route::get('/pending', [AppointmentController::class, 'pendingAppointments']);
                    Route::get('/upcoming', [AppointmentController::class, 'getDoctorUpcomingAppointments']);
                    Route::get('/done', [AppointmentController::class, 'getDoctorDoneAppointments']);
                    Route::post('/{appointment}/decide', [AppointmentController::class, 'decideAppointment']);
                    Route::post('/{appointment}/report', [AppointmentController::class, 'submitReport']);
                });
        });

    Route::prefix('video-calls')->group(function () {
        Route::post('/', [VideoCallController::class, 'store']);
        Route::get('/start/{appointment}', [VideoCallController::class, 'start']);
        Route::post('/end/{appointment}', [VideoCallController::class, 'end']);
        Route::put('/{videoCall}', [VideoCallController::class, 'update']);
    });

    Route::prefix('home-visit')->group(function () {
        Route::get('/start/{appointment}', [HomeVisitController::class, 'start']);
        Route::post('/end/{appointment}', [HomeVisitController::class, 'end']);
    });
});
