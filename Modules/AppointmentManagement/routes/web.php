<?php

use Illuminate\Support\Facades\Route;
use Modules\AppointmentManagement\App\Http\Controllers\AppointmentManagementController;

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
    Route::resource('appointmentmanagement', AppointmentManagementController::class)->names('appointmentmanagement');
});
