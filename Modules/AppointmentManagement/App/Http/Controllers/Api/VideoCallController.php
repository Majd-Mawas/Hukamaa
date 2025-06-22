<?php

namespace Modules\AppointmentManagement\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Factory;
use Modules\AppointmentManagement\App\Http\Requests\VideoCallRequest;
use Modules\AppointmentManagement\App\Http\Resources\VideoCallResource;
use Modules\AppointmentManagement\App\Models\Appointment;
use Modules\AppointmentManagement\App\Models\VideoCall;
use Modules\AppointmentManagement\App\Services\VideoCallService;
use Modules\AppointmentManagement\App\Services\ZegoTokenService;

class VideoCallController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly VideoCallService $videoCallService,
        protected ZegoTokenService $zego
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

    public function start(Appointment $appointment)
    {
        $user = Auth::user();

        if (!in_array($user->id, [$appointment->doctor_id, $appointment->patient_id])) {
            abort(403);
        }

        // Create or reuse VideoCall
        $videoCall = VideoCall::firstOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'room_id' => $this->zego->createRoomId(),
                'started_at' => now(),
            ]
        );

        if ($user->id === $appointment->doctor_id && !$videoCall->doctor_token) {
            $videoCall->doctor_token = $this->zego->generateToken("doctor_{$user->id}");
        }

        if ($user->id === $appointment->patient_id && !$videoCall->patient_token) {
            $videoCall->patient_token = $this->zego->generateToken("patient_{$user->id}");
        }

        $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials_file'));
        $messaging = $factory->createMessaging();
        $messaging->send([
            'token' => $user->fcm_token, // device token saved from mobile app
            'notification' => [
                'title' => 'Your Video Consultation is Starting Now',
                'body' => 'Please join your virtual appointment session. Your healthcare provider is waiting.',
            ],
        ]);
        $videoCall->save();

        return $this->successResponse(new VideoCallResource($videoCall));
        // return $this->successResponse([
        //     'id' => $videoCall->id,
        //     'room_id' => $videoCall->room_id,
        //     'token' => $user->id === $appointment->doctor_id
        //         ? $videoCall->doctor_token
        //         : $videoCall->patient_token,
        //     'user_id' => $user->id === $appointment->doctor_id
        //         ? "doctor_{$user->id}"
        //         : "patient_{$user->id}",
        //     'app_id' => config('services.zegocloud.app_id'),
        // ]);
    }
}
