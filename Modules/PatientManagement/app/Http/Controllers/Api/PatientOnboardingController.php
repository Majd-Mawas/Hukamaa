<?php

namespace Modules\PatientManagement\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Modules\PatientManagement\App\Http\Requests\BasicInfoRequest;
use Modules\PatientManagement\App\Http\Requests\ExtraInfoRequest;
use Modules\PatientManagement\App\Services\PatientOnboardingService;

class PatientOnboardingController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly PatientOnboardingService $patientOnboardingService
    ) {}

    public function updateBasicInfo(BasicInfoRequest $request): JsonResponse
    {
        $result = $this->patientOnboardingService->updateBasicInfo(
            $request->user()->id,
            $request->validated()
        );

        return $this->successResponse(
            $result,
            'Basic medical information updated successfully',
            201
        );
    }

    public function updateExtraInfo(ExtraInfoRequest $request): JsonResponse
    {
        $result = $this->patientOnboardingService->updateExtraInfo(
            $request->user()->id,
            $request->validated()
        );

        return $this->successResponse(
            $result,
            'Supplementary health information updated successfully',
            201
        );
    }
}
