<?php

namespace Modules\DoctorManagement\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SystemNotification;
use App\Services\NotificationTemplateBuilder;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;
use Modules\DoctorManagement\App\Http\Requests\BasicInfoRequest;
use Modules\DoctorManagement\App\Http\Requests\MedicalInfoRequest;
use Modules\DoctorManagement\App\Http\Requests\DocumentsRequest;
use Modules\DoctorManagement\App\Services\DoctorOnboardingService;
use Modules\DoctorManagement\App\Http\Resources\DoctorProfileResource;

class DoctorOnboardingController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly DoctorOnboardingService $doctorOnboardingService,
        public NotificationTemplateBuilder $notification_template_builder
    ) {}

    public function updateBasicInfo(BasicInfoRequest $request): JsonResponse
    {
        $result = $this->doctorOnboardingService->updateBasicInfo(
            $request->user()->id,
            $request->validated()
        );

        return $this->successResponse(
            new DoctorProfileResource($result->load('media', 'user')),
            'Basic information updated successfully',
            201
        );
    }

    public function updateMedicalInfo(MedicalInfoRequest $request): JsonResponse
    {
        $result = $this->doctorOnboardingService->updateMedicalInfo(
            $request->user()->id,
            $request->validated()
        );

        return $this->successResponse(
            new DoctorProfileResource($result->load('user', 'specialization', 'media')),
            'Medical information updated successfully',
            201
        );
    }

    public function uploadDocuments(DocumentsRequest $request): JsonResponse
    {
        $result = $this->doctorOnboardingService->uploadDocuments(
            $request->user()->id,
            $request->validated()
        );
        $doctor = $request->user();

        $template = $this->notification_template_builder->newDoctorSignup($doctor);

        if (env('APP_NOTIFICATION')) {
            getAdminUser()->notify(new SystemNotification(
                $template['title'],
                $template['message'],
                $template['data']
            ));
        }

        return $this->successResponse(
            new DoctorProfileResource($result->load('media', 'user', 'specialization')),
            'Documents uploaded successfully',
            201
        );
    }
}
