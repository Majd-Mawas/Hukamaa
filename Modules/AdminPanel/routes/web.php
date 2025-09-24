<?php

use Illuminate\Support\Facades\Route;
use Modules\AdminPanel\App\Http\Controllers\AllergyController;
use Modules\AdminPanel\App\Http\Controllers\AppointmentController;
use Modules\AdminPanel\App\Http\Controllers\ChronicConditionController;
use Modules\AdminPanel\App\Http\Controllers\DashboardController;
use Modules\AdminPanel\App\Http\Controllers\UsersController;
use Modules\AdminPanel\App\Http\Controllers\DoctorController;
use Modules\AdminPanel\App\Http\Controllers\PaymentController;
use Modules\AdminPanel\App\Http\Controllers\SpecializationController;
use Modules\AdminPanel\App\Http\Controllers\CoverageAreaController;
use Modules\AdminPanel\App\Http\Controllers\NotificationController;
use Modules\AdminPanel\App\Http\Controllers\PatientController;

Route::middleware(['auth'])->group(function () {
    Route::prefix('/dashboard')
        ->group(function () {

            Route::prefix('admin')->name('admin.')->group(function () {
                Route::controller(DashboardController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/profile', 'viewProfile')->name('profile');
                    Route::post('/profile', 'updateProfile')->name('profile.update');
                });

                Route::prefix('users')->name('users.')->controller(UsersController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::delete('/{user}', 'destroy')->name('destroy');
                });

                Route::prefix('doctors')->name('doctors.')->group(function () {
                    Route::controller(DoctorController::class)->group(function () {
                        Route::get('/approvals', 'doctorApprovals')->name('doctorApprovals');
                        Route::post('/{doctorProfile}/approve', 'approveDoctor')->name('approve');
                        Route::post('/{doctorProfile}/reject', 'rejectDoctor')->name('reject');
                        Route::patch('/{doctor}/update-fees', 'updateFees')->name('update-fees');
                    });
                    Route::resource('/', DoctorController::class)->parameters(['' => 'doctor'])->only(['index', 'show', 'destroy']);
                });

                Route::prefix('payments')->name('payments.')->controller(PaymentController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/pending', 'pending')->name('pending');
                    Route::post('/{payment}/approve', 'approve')->name('approve');
                    Route::post('/{payment}/reject', 'reject')->name('reject');
                });

                Route::prefix('appointments')->name('appointments.')->controller(AppointmentController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/pending', 'pending')->name('pending');
                    Route::get('/completed', 'completed')->name('completed');
                    Route::get('/{appointment}', 'show')->name('show');
                    Route::post('/{appointment}/status', 'updateStatus')->name('update-status');
                    Route::delete('/{appointment}', 'destroy')->name('destroy');
                });

                Route::prefix('specializations')->name('specializations.')->controller(SpecializationController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'store')->name('store');
                    Route::put('/{id}', 'update')->name('update');
                    Route::delete('/{id}', 'destroy')->name('destroy');
                });

                Route::prefix('coverage-areas')->name('coverageAreas.')->controller(CoverageAreaController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'store')->name('store');
                    Route::put('/{id}', 'update')->name('update');
                    Route::delete('/{id}', [CoverageAreaController::class, 'destroy'])->name('destroy');
                });

                Route::prefix('allergies')->name('allergies.')->controller(AllergyController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'store')->name('store');
                    Route::put('/{id}', 'update')->name('update');
                    Route::delete('/{id}', 'destroy')->name('destroy');
                });

                Route::prefix('chronic-condition')->name('chronicConditions.')->controller(ChronicConditionController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'store')->name('store');
                    Route::put('/{id}', 'update')->name('update');
                    Route::delete('/{id}', 'destroy')->name('destroy');
                });

                Route::prefix('patients')->name('patients.')->controller(PatientController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/{patient}', 'show')->name('show');
                    Route::delete('/{patient}', 'destroy')->name('destroy');
                });

                Route::prefix('notifications')->name('notifications.')->controller(NotificationController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/{id}/mark-as-read', 'markAsRead')->name('mark-as-read');
                    Route::post('/mark-all-as-read', 'markAllAsRead')->name('mark-all-as-read');
                });
            });
        });
});
