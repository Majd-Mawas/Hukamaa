<?php

namespace Modules\DoctorManagement\App\Http\Controllers\Api;

use App\Helpers\ArabicNumeralsHelper;
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

    public function show(DoctorProfile $doctor): JsonResponse
    {
        return $this->successResponse(
            new DoctorProfileResource($doctor->load(['user', 'specialization', 'availabilities'])),
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
        $services = $request->get('services');

        $doctors = $this->doctorProfileService->getFeaturedDoctors(
            $limit,
            $query,
            $gender,
            $specializationId,
            $services
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

        // $user->update([
        //     'name' => $data['name']
        // ]);

        if (isset($data['experience_years'])) {
            $data['experience_years'] = ArabicNumeralsHelper::convertToStandardNumerals($data['experience_years']);
        }
        $profile = $user->doctorProfile;
        $profile->update([
            'birth_date' => $data['birth_date'],
            'gender' => $data['gender'],
            'phone_number' => $data['phone_number'],
            'address' => $data['address'],
            'services' => $data['services'],
            'experience_description' => $data['experience_description'] ?? $profile->experience_description,
            'experience_years' => $data['experience_years'] ?? $profile->experience_years,
            'expertise_focus' => $data['expertise_focus'] ?? null,
        ]);


        if (isset($data['profile_picture'])) {
            $profile->clearMediaCollection('profile_picture');
            $profile->addMedia($data['profile_picture'])
                ->toMediaCollection('profile_picture');
        }

        if (isset($data['coverage_areas']) && count($data['coverage_areas']) > 0) {
            $profile->coverageAreas()->sync($data['coverage_areas']);
        } else {
            $profile->coverageAreas()->sync([]);
        }

        if (isset($data['practice_license'])) {
            $profile->clearMediaCollection('practice_licenses');
            foreach ($data['practice_license'] as $certificate) {
                $profile->addMedia($certificate)
                    ->toMediaCollection('practice_licenses');
            }
        }

        if (isset($data['medical_certificates'])) {
            $profile->clearMediaCollection('medical_certificates');
            foreach ($data['medical_certificates'] as $certificate) {
                $profile->addMedia($certificate)
                    ->toMediaCollection('medical_certificates');
            }
        }

        return $this->successResponse(
            new DoctorProfileResource($profile->load('media', 'user', 'specialization', 'availabilities')),
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

            foreach ($data['availabilities'] as $availability) {
                $profile->availabilities()->create([
                    'weekday' => $availability['weekday'],
                    'start_time' => $availability['start_time'],
                    'end_time' => $availability['end_time'],
                    'doctor_id' => $profile->id
                ]);
            }
        });

        return $this->successResponse(
            new DoctorProfileResource($profile->load(['user', 'availabilities', 'coverageAreas'])),
            'Availabilities updated successfully'
        );
    }

    public function verifyStatus(): JsonResponse
    {
        $doctorProfile = DoctorProfile::where('user_id', Auth::id())->first();

        if (!$doctorProfile) {
            return $this->errorResponse('Doctor profile not found', 404);
        }

        return $this->successResponse([
            'status' => $doctorProfile->status,
            'is_approved' => $doctorProfile->status === 'approved'
        ], 'Doctor status retrieved successfully');
    }
}
