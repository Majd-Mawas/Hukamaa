<?php

namespace Modules\DoctorManagement\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\DoctorManagement\App\Http\Requests\DoctorProfileRequest;
use Modules\DoctorManagement\App\Http\Requests\UpdateDoctorAvailabilitiesRequest;
use Modules\DoctorManagement\App\Http\Requests\UpdateDoctorProfileRequest;
use Modules\DoctorManagement\App\Http\Resources\CoverageAreaResource;
use Modules\DoctorManagement\App\Http\Resources\DoctorProfileResource;
use Modules\DoctorManagement\App\Models\CoverageArea;
use Modules\DoctorManagement\App\Models\DoctorProfile;
use Modules\DoctorManagement\App\Services\DoctorProfileService;
use Modules\DoctorManagement\App\Services\DoctorStatisticsService;

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

    public function statistics(DoctorStatisticsService $statisticsService): JsonResponse
    {
        $statistics = $statisticsService->getDoctorStatistics(Auth::id());

        return $this->successResponse(
            $statistics,
            __('doctormanagement::doctor.messages.statistics_retrieved_successfully')
        );
    }

    public function getCoverageAreas()
    {
        return $this->successResponse(
            CoverageAreaResource::collection(CoverageArea::all())
        );
    }

    public function updateProfile(UpdateDoctorProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();

        // Update user's name
        $user->update([
            'name' => $data['name']
        ]);

        // Update doctor profile
        $profile = $user->doctorProfile;
        $profile->update([
            'birth_date' => $data['birth_date'],
            'gender' => $data['gender'],
            'phone_number' => $data['phone_number'],
            'address' => $data['address']
        ]);

        // Update coverage areas
        $profile->coverageAreas()->sync($data['coverage_areas']);

        return $this->successResponse(
            new DoctorProfileResource($profile->load(['user', 'availabilities', 'coverageAreas'])),
            'Profile updated successfully'
        );
    }

    public function updateAvailabilities(UpdateDoctorAvailabilitiesRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();
        $profile = $user->doctorProfile;

        DB::transaction(function () use ($profile, $data) {
            $profile->availabilities()->delete();

            $availabilities = collect($data['availabilities'])->map(function ($availability) {
                return [
                    'weekday' => $availability['weekday'],
                    'start_time' => $availability['start_time'],
                    'end_time' => $availability['end_time'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            })->all();

            $profile->availabilities()->insert($availabilities);
        });

        return $this->successResponse(
            new DoctorProfileResource($profile->load(['user', 'availabilities', 'coverageAreas'])),
            'Availabilities updated successfully'
        );
    }
}
