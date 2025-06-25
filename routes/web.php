<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::view('/privacy-policy', 'privacy-policy')->name('privacy.policy');

Route::view('/', 'comingSoon')->name('comingSoon');
Route::controller(AuthController::class)
    ->group(function () {
        Route::middleware('guest')->group(function () {
            Route::get('/login', 'signin')->name('login');
            Route::post('/login', 'authenticate')->name('authenticate');
        });

        Route::post('/logout', 'logout')
            ->name('logout')
            ->middleware('auth');
    });
