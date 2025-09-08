<?php

namespace Modules\UserManagement\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Notifications\SystemNotification;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Modules\DoctorManagement\App\Enums\UserRole;
use Modules\UserManagement\App\Http\Requests\LoginRequest;
use Modules\UserManagement\App\Http\Requests\RegisterRequest;
use Modules\UserManagement\App\Http\Resources\UserResource;
use Modules\UserManagement\App\Models\User;
use Modules\UserManagement\App\Notifications\VerifyEmailNotification;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request): JsonResponse
    {
        // Validate if email exists and is real
        $email = $request->validated()['email'];

        // Check if email domain exists and verify email format
        $domain = substr(strrchr($email, "@"), 1);

        if (!checkdnsrr($domain, "MX")) {
            return $this->errorResponse(
                __('validation.email'),
                422
            );
        }

        // Additional check for common email providers
        if (in_array($domain, ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com'])) {
            // You could integrate with email verification services here
            // For now, we'll proceed with basic validation
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->errorResponse(
                    __('validation.email'),
                    422
                );
            }
        }

        // Start a database transaction
        DB::beginTransaction();

        try {
            $user = User::create($request->validated());
            $token = $user->createToken('auth_token')->plainTextToken;

            if ($user->role !== UserRole::PATIENT->value) {
                $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

                DB::table('email_verifications')->updateOrInsert(
                    ['user_id' => $user->id],
                    [
                        'code' => $code,
                        'created_at' => now(),
                        'expires_at' => now()->addMinutes(60),
                    ]
                );

                try {
                    // Attempt to send verification email
                    $user->notify(new VerifyEmailNotification($code));
                } catch (\Exception $e) {
                    // If email sending fails, rollback the transaction and return error
                    DB::rollBack();

                    // Log the error for debugging
                    logger()->error('Email verification failed: ' . $e->getMessage());

                    return $this->errorResponse(
                        __('validation.email_invalid_or_nonexistent'),
                        422
                    );
                }
            }

            if (isset(request()->fcm_token)) {
                $user->update([
                    'fcm_token' => request()->fcm_token
                ]);
            }

            // If everything is successful, commit the transaction
            DB::commit();

            return $this->successResponse(
                [
                    'user' => new UserResource($user),
                    'token' => $token
                ],
                'Registration successful. Please check your email for verification.',
                201
            );
        } catch (\Exception $e) {
            // If any other error occurs, rollback the transaction
            DB::rollBack();

            // Log the error for debugging
            logger()->error('Registration failed: ' . $e->getMessage());

            return $this->errorResponse(
                __('validation.registration_failed'),
                500
            );
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->validated())) {
            return $this->errorResponse(
                'Invalid credentials',
                401
            );
        }

        $user = User::where('email', $request->email)->first();

        // if (!$user->hasVerifiedEmail()) {
        //     return $this->errorResponse(
        //         'Please verify your email address before logging in.',
        //         403
        //     );
        // }

        $token = $user->createToken('auth_token')->plainTextToken;

        if (isset(request()->fcm_token)) {
            $user->update([
                'fcm_token' => request()->fcm_token
            ]);
        }

        if ($request->header('fcm-token')) {
            $user->update([
                'fcm_token' => $request->header('fcm-token')
            ]);
        }

        if (request()->header('time-zone')) {
            Auth::user()->update([
                'timezone' => request()->header('time-zone')
            ]);
        }

        return $this->successResponse(
            [
                'user' => new UserResource($user->load('patientProfile', 'doctorProfile')),
                'token' => $token
            ],
            'Login successful'
        );
    }

    public function logout(): JsonResponse
    {
        Auth::user()?->currentAccessToken()?->delete();

        return $this->successResponse(
            null,
            'Successfully logged out'
        );
    }

    public function verifyToken(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return $this->errorResponse(
                'Invalid or expired token',
                401
            );
        }

        if (isset(request()->fcm_token)) {
            $user->update([
                'fcm_token' => request()->fcm_token
            ]);
        }

        if (request()->header('fcm-token')) {
            $user->update([
                'fcm_token' => request()->header('fcm-token')
            ]);
        }

        if (request()->header('time-zone')) {
            Auth::user()->update([
                'timezone' => request()->header('time-zone')
            ]);
        }

        return $this->successResponse(
            [
                'user' => new UserResource($user->load('patientProfile', 'doctorProfile')),
            ],
        );
    }


    public function destroy(User $user)
    {
        $user->delete();

        return $this->successResponse(
            null,
            'User Deleted Successfully'
        );
    }
}
