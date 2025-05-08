<?php

use Illuminate\Support\Facades\Route;
use Modules\PatientManagement\App\Http\Controllers\PatientManagementController;

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

Route::group([], function () {
    Route::resource('patientmanagement', PatientManagementController::class)->names('patientmanagement');
});
