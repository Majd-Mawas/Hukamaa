<?php

namespace Modules\UserManagement\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\UserManagement\App\Http\Requests\ForgotPasswordRequest;
use Modules\UserManagement\App\Http\Requests\VerifyCodeRequest;
use Modules\UserManagement\App\Http\Requests\SetNewPasswordRequest;
use Modules\UserManagement\App\Models\User;
use Modules\UserManagement\App\Notifications\ResetPasswordNotification;

class PasswordResetController extends Controller
{
    use ApiResponse;

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->errorResponse('User not found', 404);
        }

        // Generate a 6-digit verification code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($code),
                'created_at' => now(),
            ]
        );

        $user->notify(new ResetPasswordNotification($code));

        return $this->successResponse(
            null,
            __('usermanagement::usermanagement.messages.verification_code_sent')
        );
    }

    public function verifyCode(VerifyCodeRequest $request): JsonResponse
    {
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset || !Hash::check($request->code, $passwordReset->token)) {
            return $this->errorResponse('Invalid verification code', 400);
        }

        if (now()->diffInHours($passwordReset->created_at) > 24) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return $this->errorResponse(
                __('usermanagement::usermanagement.messages.verification_code_expired'),
                400
            );
        }

        return $this->successResponse(
            ['code' => $request->code, 'email' => $request->email],
            __('usermanagement::usermanagement.messages.code_verified')
        );
    }

    public function setNewPassword(SetNewPasswordRequest $request): JsonResponse
    {
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset || !Hash::check($request->code, $passwordReset->token)) {
            return $this->errorResponse('Invalid verification code', 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return $this->successResponse(
            null,
            __('usermanagement::usermanagement.messages.password_reset_successful')
        );
    }
}
