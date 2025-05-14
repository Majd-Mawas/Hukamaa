<?php

use Illuminate\Support\Facades\Route;
use Modules\SpecializationManagement\App\Http\Controllers\Api\SpecializationController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

Route::middleware(['auth:sanctum'])->name('api.')->group(function () {
    Route::prefix('specializations')->name('specialization-management.')->group(function () {
        Route::get('/', [SpecializationController::class, 'index'])->name('index');
        Route::post('/', [SpecializationController::class, 'store'])->name('store');
        Route::get('/{specialization}', [SpecializationController::class, 'show'])->name('show');
        Route::put('/{specialization}', [SpecializationController::class, 'update'])->name('update');
        Route::delete('/{specialization}', [SpecializationController::class, 'destroy'])->name('destroy');
    });
});
