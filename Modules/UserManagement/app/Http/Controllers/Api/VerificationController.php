<?php

namespace Modules\UserManagement\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\UserManagement\Models\User;
use Modules\UserManagement\Services\EmailVerificationService;

class VerificationController extends Controller
{
    use ApiResponse;

    protected EmailVerificationService $verificationService;

    public function __construct(EmailVerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    public function verify(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);

        if ($user->hasVerifiedEmail()) {
            return $this->errorResponse(
                'Email already verified',
                400
            );
        }

        if ($this->verificationService->verifyEmail($user, $request->hash)) {
            event(new Verified($user));
            return $this->successResponse(
                null,
                'Email has been verified'
            );
        }

        return $this->errorResponse(
            'Invalid verification link',
            400
        );
    }

    public function resend(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->errorResponse(
                'Email already verified',
                400
            );
        }

        $this->verificationService->resendVerificationEmail($request->user());

        return $this->successResponse(
            null,
            'Verification link sent'
        );
    }
}
