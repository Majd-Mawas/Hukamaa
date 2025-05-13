<?php

namespace Modules\DoctorManagement\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\DoctorManagement\App\Http\Requests\DoctorProfileRequest;
use Modules\DoctorManagement\App\Http\Resources\DoctorProfileResource;
use Modules\DoctorManagement\App\Models\DoctorProfile;
use Modules\DoctorManagement\App\Services\DoctorProfileService;

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

    /**
     * Get featured doctors for the home page with search capabilities
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function featured(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        $query = $request->get('query');
        $gender = $request->get('gender');
        $specializationId = $request->get('specialization_id');

        $doctors = $this->doctorProfileService->getFeaturedDoctors(
            $limit,
            $query,
            $gender,
            $specializationId
        );

        return $this->successResponse(
            DoctorProfileResource::collection($doctors),
            'Featured doctors retrieved successfully'
        );
    }
}
