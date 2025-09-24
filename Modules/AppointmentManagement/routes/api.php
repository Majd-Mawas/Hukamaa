<?php

use Illuminate\Support\Facades\Route;
use Modules\AppointmentManagement\App\Http\Controllers\Api\AppointmentController;
use Modules\AppointmentManagement\App\Http\Controllers\Api\HomeVisitController;
use Modules\AppointmentManagement\App\Http\Controllers\Api\VideoCallController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('appointments')
        ->controller(AppointmentController::class)
        ->name('appointments.')
        ->group(function () {
            Route::post('/', 'confirmDateTime')->name('store');
            // Route::post('/datetime', [AppointmentController::class, 'confirmDateTime']);
            Route::get('/available-slots', 'getAvailableSlots');
            Route::get('/upcoming', 'getUpcomingAppointments');
            Route::get('/done', 'getDoneAppointments');
            Route::post('/{appointment}', 'update')->name('update');
            Route::post('/{appointment}/cancel', 'cancel');
            Route::prefix('/{appointment}/confirm')->group(function () {
                Route::post('payment', 'confirmPayment');
            });
            Route::prefix('doctor')
                ->controller(AppointmentController::class)
                ->name('doctor.')
                ->group(function () {
                    Route::get('/pending', 'pendingAppointments');
                    Route::get('/upcoming', 'getDoctorUpcomingAppointments');
                    Route::get('/done', 'getDoctorDoneAppointments');
                    Route::post('/{appointment}/decide', 'decideAppointment');
                    Route::post('/{appointment}/report', 'submitReport');
                    Route::put('/{appointment}/report', 'updateReport');
                });
        });

    Route::prefix('video-calls')
        ->controller(VideoCallController::class)
        ->name('video-calls.')
        ->group(function () {
            Route::post('/', 'store');
            Route::get('/start/{appointment}', 'start');
            Route::post('/end/{appointment}', 'end');
            Route::put('/{videoCall}', 'update');
        });

    Route::prefix('home-visit')
        ->controller(HomeVisitController::class)
        ->name('home-visit.')
        ->group(function () {
            Route::get('/start/{appointment}', 'start');
            Route::post('/end/{appointment}', 'end');
        });
});
