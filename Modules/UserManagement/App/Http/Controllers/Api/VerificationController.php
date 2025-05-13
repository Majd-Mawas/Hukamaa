<?php

namespace Modules\UserManagement\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\UserManagement\App\Models\User;
use Modules\UserManagement\App\Services\EmailVerificationService;

class VerificationController extends Controller
{
    use ApiResponse;

    protected EmailVerificationService $verificationService;

    public function __construct(EmailVerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $user = $request->user();

        $verification = DB::table('email_verifications')
            ->where('user_id', $user->id)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return $this->errorResponse(
                'Invalid or expired verification code',
                400
            );
        }

        if ($user->hasVerifiedEmail()) {
            return $this->errorResponse(
                'Email already verified',
                400
            );
        }

        $user->markEmailAsVerified();
        DB::table('email_verifications')->where('user_id', $user->id)->delete();

        return $this->successResponse(
            null,
            'Email has been verified'
        );
    }

    public function resend(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return $this->errorResponse(
                'Email already verified',
                400
            );
        }

        // Generate a 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store the code in the database
        DB::table('email_verifications')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'code' => $code,
                'created_at' => now(),
                'expires_at' => now()->addMinutes(60),
            ]
        );

        // Send the email
        $user->notify(new \Modules\UserManagement\App\Notifications\VerifyEmailNotification($code));

        return $this->successResponse(
            null,
            'Verification code sent'
        );
    }
}
