<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('account-number', function () {
    return response()->json([
        'Account_Number' => getSetting('account_number'),
        'Account_Holder_Name' => getSetting('account_holder_name'),
        'Bank_Name' => getSetting('bank_name')
    ]);
});
