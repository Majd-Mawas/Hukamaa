<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function signin()
    {
        return view('authentication.signin');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();
        return $this->redirectBasedOnRole();
    }

    /**
     * Redirect users based on their role
     */
    private function redirectBasedOnRole(): \Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();

        return match ($user->role) {
            // 'admin' => redirect()->route('admin.dashboard'),
            'admin' => redirect()->route('admin.index'),
            'doctor' => redirect()->route('doctor.index'),
            default => redirect()->route('comingSoon'),
        };
    }

    public function logout(Request $request)
    {
        Auth::user()->update([
            'fcm_token' => null
        ]);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
