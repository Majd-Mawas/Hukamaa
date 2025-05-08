<?php

namespace Modules\DoctorManagement\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\DoctorManagement\Http\Requests\DoctorProfileRequest;
use Modules\DoctorManagement\Http\Resources\DoctorProfileResource;
use Modules\DoctorManagement\Models\DoctorProfile;
use Modules\DoctorManagement\Services\DoctorProfileService;

class DoctorProfileController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly DoctorProfileService $doctorProfileService
    ) {}

    public function index(): JsonResponse
    {
        $doctors = $this->doctorProfileService->getAllDoctors();

        return $this->successResponse(
            DoctorProfileResource::collection($doctors),
            'Doctors retrieved successfully'
        );
    }

    public function store(DoctorProfileRequest $request): JsonResponse
    {
        $doctorProfile = $this->doctorProfileService->createDoctorProfile($request->validated());

        return $this->successResponse(
            new DoctorProfileResource($doctorProfile),
            'Doctor profile created successfully',
            201
        );
    }

    public function show(DoctorProfile $doctorProfile): JsonResponse
    {
        return $this->successResponse(
            new DoctorProfileResource($doctorProfile->load(['user', 'specialization', 'availabilities'])),
            'Doctor profile retrieved successfully'
        );
    }

    public function update(DoctorProfileRequest $request, DoctorProfile $doctorProfile): JsonResponse
    {
        $updatedProfile = $this->doctorProfileService->updateDoctorProfile($doctorProfile, $request->validated());

        return $this->successResponse(
            new DoctorProfileResource($updatedProfile),
            'Doctor profile updated successfully'
        );
    }

    public function destroy(DoctorProfile $doctorProfile): JsonResponse
    {
        $this->doctorProfileService->deleteDoctorProfile($doctorProfile);

        return $this->successResponse(
            null,
            'Doctor profile deleted successfully'
        );
    }
}
