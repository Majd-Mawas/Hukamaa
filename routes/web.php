<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


Route::controller(HomeController::class)->group(function () {
    Route::get('/privacy-policy', 'privacyPolicy')->name('privacy.policy');
    Route::get('/', 'comingSoon')->name('comingSoon');
});

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

Route::get('nothing', function () {
    return "nothing";
})->name('index');
