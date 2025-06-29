<?php

use Illuminate\Support\Facades\Route;
use Modules\DoctorManagement\App\Http\Controllers\Web\AppointmentController;
use Modules\DoctorManagement\App\Http\Controllers\Web\DashboardController;
use Modules\DoctorManagement\App\Http\Controllers\Web\PatientController;
use Modules\DoctorManagement\App\Http\Controllers\Web\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function () {
    Route::prefix('/dashboard')
        ->group(function () {

            Route::prefix('doctor')->name('doctor.')->group(function () {
                Route::controller(DashboardController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/profile', 'viewProfile')->name('profile');
                    Route::post('/profile', 'updateProfile')->name('profile.update');
                    Route::get('availabilities', [DashboardController::class, 'getAvailabilities']);
                    Route::get('availability/{id}', [DashboardController::class, 'getAvailability']);
                    Route::post('availability', [DashboardController::class, 'storeAvailability']);
                    Route::put('availability/{id}', [DashboardController::class, 'updateAvailability']);
                    Route::delete('availability/{id}', [DashboardController::class, 'deleteAvailability']);
                });

                Route::prefix('payments')->name('payments.')->group(function () {
                    Route::get('/', [PaymentController::class, 'index'])->name('index');
                    // Route::get('/pending', [PaymentController::class, 'pending'])->name('pending');
                    // Route::post('/{payment}/approve', [PaymentController::class, 'approve'])->name('approve');
                    // Route::post('/{payment}/reject', [PaymentController::class, 'reject'])->name('reject');
                });

                Route::prefix('appointments')->name('appointments.')->controller(AppointmentController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/new', 'new')->name('new');
                    Route::get('/upcoming', 'upcoming')->name('upcoming');
                    Route::get('/{appointment}', 'show')->name('show');
                    Route::get('/{appointment}/accept', 'accept')->name('accept');
                    Route::get('/{appointment}/reject', 'reject')->name('reject');
                    Route::post('/{appointment}/status', 'updateStatus')->name('update-status');
                });

                Route::prefix('patients')->name('patients.')->controller(PatientController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/{patient}/edit', 'edit')->name('edit');
                    Route::get('/{patient}', 'show')->name('show');
                    Route::put('/{patient}', 'update')->name('update');
                });
            });
        });
});
