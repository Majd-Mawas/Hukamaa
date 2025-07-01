<?php

namespace Modules\UserManagement\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\UserManagement\App\Http\Requests\LoginRequest;
use Modules\UserManagement\App\Http\Requests\RegisterRequest;
use Modules\UserManagement\App\Http\Resources\UserResource;
use Modules\UserManagement\App\Models\User;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());
        $token = $user->createToken('auth_token')->plainTextToken;

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

        // Send verification email
        $user->notify(new \Modules\UserManagement\App\Notifications\VerifyEmailNotification($code));

        if ($request->header('fcm-token')) {
            $user->update([
                'fcm_token' => $request->header('fcm-token')
            ]);
        }

        if (isset(request()->fcm_token)) {
            $user->update([
                'fcm_token' => request()->fcm_token
            ]);
        }

        return $this->successResponse(
            [
                'user' => new UserResource($user),
                'token' => $token
            ],
            'Registration successful. Please check your email for verification.',
            201
        );
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

        return $this->successResponse(
            [
                'user' => new UserResource($user->load('patientProfile', 'doctorProfile')),
            ],
        );
    }
}
