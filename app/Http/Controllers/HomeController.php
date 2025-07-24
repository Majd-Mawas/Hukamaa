<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the privacy policy page.
     *
     * @return \Illuminate\View\View
     */
    public function privacyPolicy()
    {
        return view('privacy-policy');
    }

    /**
     * Show the privacy policy page.
     *
     * @return \Illuminate\View\View
     */
    public function privacyPolicyPatient()
    {
        return view('privacy-policy-patient');
    }

    /**
     * Show the privacy policy page.
     *
     * @return \Illuminate\View\View
     */
    public function privacyPolicyDoctor()
    {
        return view('privacy-policy-doctor');
    }

    public function comingSoon(): View|RedirectResponse
    {
        // if (Auth::check()) {
        //     return $this->redirectBasedOnRole();
        // }

        return view('comingSoon');
    }

    private function redirectBasedOnRole(): RedirectResponse
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.index'),
            'doctor' => redirect()->route('doctor.index'),
            default => redirect()->route('comingSoon'),
        };
    }
}
