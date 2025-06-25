<?php

use Illuminate\Support\Facades\Route;
use Modules\AdminPanel\App\Http\Controllers\AuthenticationController;
use Modules\DoctorManagement\App\Http\Controllers\Web\AppointmentController;
use Modules\DoctorManagement\App\Http\Controllers\Web\CoverageAreaController;
use Modules\DoctorManagement\App\Http\Controllers\Web\DashboardController;
use Modules\DoctorManagement\App\Http\Controllers\Web\DoctorController;
use Modules\DoctorManagement\App\Http\Controllers\Web\PatientController;
use Modules\DoctorManagement\App\Http\Controllers\Web\PaymentController;
use Modules\DoctorManagement\App\Http\Controllers\Web\SpecializationController;

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
                });

                Route::prefix('doctors')->name('doctors.')->group(function () {
                    Route::controller(DoctorController::class)->group(function () {
                        Route::get('/approvals', 'doctorApprovals')->name('doctorApprovals');
                        Route::post('/{doctorProfile}/approve', 'approveDoctor')->name('approve');
                        Route::post('/{doctorProfile}/reject', 'rejectDoctor')->name('reject');
                    });
                    Route::resource('/', DoctorController::class)->parameters(['' => 'doctor']);
                });

                Route::prefix('payments')->name('payments.')->group(function () {
                    Route::get('/', [PaymentController::class, 'index'])->name('index');
                    Route::get('/pending', [PaymentController::class, 'pending'])->name('pending');
                    Route::post('/{payment}/approve', [PaymentController::class, 'approve'])->name('approve');
                    Route::post('/{payment}/reject', [PaymentController::class, 'reject'])->name('reject');
                });

                Route::prefix('appointments')->name('appointments.')->controller(AppointmentController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/pending', 'pending')->name('pending');
                    Route::get('/completed', 'completed')->name('completed');
                    Route::get('/{appointment}', 'show')->name('show');
                    Route::post('/{appointment}/status', 'updateStatus')->name('update-status');
                });

                Route::prefix('specializations')->name('specializations.')->controller(SpecializationController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'store')->name('store');
                    Route::put('/{id}', 'update')->name('update');
                    Route::delete('/{id}', 'destroy')->name('destroy');
                });

                Route::prefix('coverage-areas')->name('coverageAreas.')->group(function () {
                    Route::get('/', [CoverageAreaController::class, 'index'])->name('index');
                    Route::post('/', [CoverageAreaController::class, 'store'])->name('store');
                    Route::put('/{id}', [CoverageAreaController::class, 'update'])->name('update');
                    Route::delete('/{id}', [CoverageAreaController::class, 'destroy'])->name('destroy');
                });

                Route::prefix('patients')->name('patients.')->controller(PatientController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/{patient}', 'show')->name('show');
                });
            });
        });

    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
});
