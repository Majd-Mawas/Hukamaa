<?php

namespace Modules\AppointmentManagement\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Modules\AppointmentManagement\Http\Requests\VideoCallRequest;
use Modules\AppointmentManagement\Http\Resources\VideoCallResource;
use Modules\AppointmentManagement\Models\VideoCall;
use Modules\AppointmentManagement\Services\VideoCallService;

class VideoCallController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly VideoCallService $videoCallService
    ) {}

    public function store(VideoCallRequest $request): JsonResponse
    {
        $videoCall = $this->videoCallService->createVideoCall($request->validated());

        return $this->successResponse(
            new VideoCallResource($videoCall),
            'Video call session created successfully',
            201
        );
    }

    public function update(VideoCallRequest $request, VideoCall $videoCall): JsonResponse
    {
        $updatedVideoCall = $this->videoCallService->updateVideoCall($videoCall, $request->validated());

        return $this->successResponse(
            new VideoCallResource($updatedVideoCall),
            'Video call session updated successfully'
        );
    }

    public function end(VideoCall $videoCall): JsonResponse
    {
        $endedVideoCall = $this->videoCallService->endVideoCall($videoCall);

        return $this->successResponse(
            new VideoCallResource($endedVideoCall),
            'Video call session ended successfully'
        );
    }
}
