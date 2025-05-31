<?php

namespace Modules\AdminPanel\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AiapplicationController extends Controller
{
    public function codeGenerator()
    {
        return view('aiapplication/codeGenerator');
    }

    public function codeGeneratorNew()
    {
        return view('aiapplication/codeGeneratorNew');
    }

    public function imageGenerator()
    {
        return view('aiapplication/imageGenerator');
    }

    public function textGenerator()
    {
        return view('aiapplication/textGenerator');
    }

    public function textGeneratorNew()
    {
        return view('aiapplication/textGeneratorNew');
    }

    public function videoGenerator()
    {
        return view('aiapplication/videoGenerator');
    }

    public function VoiceGenerator()
    {
        return view('aiapplication/VoiceGenerator   ');
    }
}
