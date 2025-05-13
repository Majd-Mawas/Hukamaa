<?php

namespace Modules\PatientManagement\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePatientRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->role !== 'patient') {
            return response()->json([
                'message' => 'Unauthorized. Required role: patient'
            ], 403);
        }

        return $next($request);
    }
}
