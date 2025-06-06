<?php

namespace Modules\AdminPanel\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function codeGenerator()
    {
        return view('aiapplication/codeGenerator');
    }

    public function company()
    {
        return view('settings/company');
    }

    public function currencies()
    {
        return view('settings/currencies');
    }

    public function language()
    {
        return view('settings/language');
    }

    public function notification()
    {
        return view('settings/notification');
    }

    public function notificationAlert()
    {
        return view('settings/notificationAlert');
    }

    public function paymentGateway()
    {
        return view('settings/paymentGateway');
    }

    public function theme()
    {
        return view('settings/theme');
    }
}
