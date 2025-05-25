<?php

namespace Modules\UserManagement\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\UserManagement\App\Http\Requests\ForgotPasswordRequest;
use Modules\UserManagement\App\Http\Requests\ResetPasswordRequest;
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

        $token = Str::random(32);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        $user->notify(new ResetPasswordNotification($token));


        return $this->successResponse(
            null,
            __('usermanagement::usermanagement.messages.password_reset_link_sent')
        );
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
            return $this->errorResponse('Invalid token', 400);
        }

        if (now()->diffInHours($passwordReset->created_at) > 24) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return $this->errorResponse(
                __('usermanagement::usermanagement.messages.password_reset_token_expired'),
                400
            );
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
