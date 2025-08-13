<?php

use Illuminate\Support\Facades\Route;
use Modules\DoctorManagement\App\Http\Controllers\Web\AppointmentController;
use Modules\DoctorManagement\App\Http\Controllers\Web\DashboardController;
use Modules\DoctorManagement\App\Http\Controllers\Web\PatientController;
use Modules\DoctorManagement\App\Http\Controllers\Web\PaymentController;
use Modules\DoctorManagement\App\Http\Controllers\Web\AvailabilityController;
use Modules\DoctorManagement\App\Http\Controllers\Web\NotificationController;

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
                    Route::post('/{appointment}/update-time', 'updateTime')->name('update-time'); // New route for updating appointment time
                });

                Route::prefix('patients')->name('patients.')->controller(PatientController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/{patient}/edit', 'edit')->name('edit');
                    Route::get('/{patient}', 'show')->name('show');
                    Route::put('/{patient}', 'update')->name('update');
                });

                Route::prefix('availabilities')->name('availabilities.')->controller(AvailabilityController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/{id}', 'show')->name('show');
                    Route::post('/', 'store')->name('store');
                    Route::put('/{id}', 'update')->name('update');
                    Route::delete('/{id}', 'destroy')->name('destroy');
                });

                Route::prefix('notifications')->name('notifications.')->controller(NotificationController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/{id}/mark-as-read', 'markAsRead')->name('mark-as-read');
                    Route::post('/mark-all-as-read', 'markAllAsRead')->name('mark-all-as-read');
                });
            });
        });
});
