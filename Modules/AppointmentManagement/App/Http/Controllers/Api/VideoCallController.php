<?php

namespace Modules\AppointmentManagement\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
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
        $patient = User::findOrFail($appointment->patient_id);

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

        $token = $patient->fcm_token;

        // if ($token) {
        $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials_file'));

        $messaging = $factory->createMessaging();
        $messaging->send([
            'token' => $patient->fcm_token,
            'data' => [
                'event' => 'call_invitation',
                'room_id' => $videoCall->room_id,
                'appointment_id' => (string)$appointment->id,
                'app_id' => (string)config('services.zegocloud.app_id'),
                'user_id' => "patient_{$patient->id}",
                'token' => $videoCall->patient_token,
                'caller_id' => (string)$user->id,
                'caller_name' => $user->name,
                'caller_type' => "doctor",
            ],
            'notification' => [
                'title' => 'Incoming Video Call',
                'body' => 'Your doctor is calling you for your appointment.',
            ],
        ]);
        // } else {
        //     // Optionally log this
        //     \Log::warning("No FCM token found for patient ID {$patient->id}");
        // }

        $videoCall->save();

        return $this->successResponse(new VideoCallResource($videoCall));
    }
}
