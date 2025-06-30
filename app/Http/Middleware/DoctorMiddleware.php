<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\DoctorManagement\App\Enums\UserRole;
use Symfony\Component\HttpFoundation\Response;

class DoctorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (!(Auth::check()) || !(Auth::user()->role == UserRole::DOCTOR->value)) {
            return $this->redirectBasedOnRole();
        }

        return $next($request);
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
