<?php

namespace Modules\PatientManagement\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Modules\PatientManagement\Http\Requests\BasicInfoRequest;
use Modules\PatientManagement\Http\Requests\ExtraInfoRequest;
use Modules\PatientManagement\Http\Resources\PatientProfileResource;
use Modules\PatientManagement\Services\PatientOnboardingService;

class PatientOnboardingController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly PatientOnboardingService $onboardingService
    ) {}

    public function updateBasicInfo(BasicInfoRequest $request): JsonResponse
    {
        $profile = $this->onboardingService->updateBasicInfo(
            $request->user()->id,
            $request->validated()
        );

        return $this->successResponse(
            new PatientProfileResource($profile),
            'Basic medical information updated successfully',
            201
        );
    }

    public function updateExtraInfo(ExtraInfoRequest $request): JsonResponse
    {
        $profile = $this->onboardingService->updateExtraInfo(
            $request->user()->id,
            $request->validated()
        );

        return $this->successResponse(
            new PatientProfileResource($profile->load('media')),
            'Supplementary health information updated successfully',
            201
        );
    }
}
