<?php

namespace Modules\AdminPanel\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CryptocurrencyController extends Controller
{
    public function wallet()
    {
        return view('cryptocurrency/wallet');
    }
}
