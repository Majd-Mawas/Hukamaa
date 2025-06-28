<?php

namespace Modules\PatientManagement\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Modules\PatientManagement\App\Http\Requests\UpdateProfileRequest;
use Modules\PatientManagement\App\Http\Resources\AllergyResource;
use Modules\PatientManagement\App\Models\Allergy;
use Modules\PatientManagement\App\Services\PatientProfileService;
use Modules\PatientManagement\App\Models\ChronicCondition;
use Modules\PatientManagement\App\Http\Resources\ChronicConditionResource;

class PatientProfileController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly PatientProfileService $patientProfileService
    ) {}

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $request->user();

        $profile = $this->patientProfileService->updateProfile($user->id, $data);

        return $this->successResponse(
            $profile,
            __('patientmanagement::messages.profile_updated')
        );
    }

    public function getAllergies()
    {
        return $this->successResponse(
            AllergyResource::collection(Allergy::all())
        );
    }
    public function getChronicConditions()
    {
        return $this->successResponse(
            ChronicConditionResource::collection(ChronicCondition::all())
        );
    }
}
