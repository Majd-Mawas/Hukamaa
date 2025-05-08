<?php

namespace Modules\UserManagement\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\UserManagement\Http\Requests\LoginRequest;
use Modules\UserManagement\Http\Requests\RegisterRequest;
use Modules\UserManagement\Http\Resources\UserResource;
use Modules\UserManagement\Models\User;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse(
            [
                'user' => new UserResource($user),
                'token' => $token
            ],
            'Registration successful',
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
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse(
            [
                'user' => new UserResource($user),
                'token' => $token
            ],
            'Login successful'
        );
    }

    public function logout(): JsonResponse
    {
        auth()->user()->currentAccessToken()->delete();

        return $this->successResponse(
            null,
            'Successfully logged out'
        );
    }

    public function verifyToken(): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return $this->errorResponse(
                'Invalid or expired token',
                401
            );
        }

        return $this->successResponse(
            new UserResource($user),
            'Token is valid'
        );
    }
}
