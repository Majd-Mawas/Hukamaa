<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::view('/privacy-policy', 'privacy-policy')->name('privacy.policy');

Route::controller(AuthController::class)
    ->middleware('guest')
    ->group(function () {
        Route::get('/login', 'signin')->name('login');
        Route::post('/login', 'authenticate')->name('authenticate');
    });
