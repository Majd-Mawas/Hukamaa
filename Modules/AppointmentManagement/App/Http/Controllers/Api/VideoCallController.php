<?php

namespace Modules\AppointmentManagement\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SystemNotification;
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

    public function end(Appointment $appointment): JsonResponse
    {
        $endedVideoCall = $this->videoCallService->endVideoCall($appointment);

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


        $videoCall->save();
        $videoCall->refresh();

        $token = $patient->fcm_token;
        if ($token) {

            $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials_file'));

            $messaging = $factory->createMessaging();
            $videoCallData = [
                'started_at' => $videoCall->started_at?->toISOString(),
                'ended_at' => $videoCall->ended_at?->toISOString(),
                'call_duration' => $videoCall->call_duration,
                'status' => $videoCall->status,
                'room_id' => $videoCall->room_id,
                'appointment_id' => $videoCall->appointment_id,
                'token' => $videoCall->patient_token,
                'user_id' => "patient_{$user->id}",
                'app_id' => config('services.zegocloud.app_id'),
                'app_sign' => config('services.zegocloud.app_sign'),
                "title" => 'مكالمة فيديو واردة',
                "message" => 'طبيبك يتصل بك لموعدك.',
            ];

            $messaging->send([
                'token' => $patient->fcm_token,
                'data' => collect(['event' => 'call_invitation'])
                    ->merge($this->stringifyArray($videoCallData))
                    ->all(),
            ]);
        }

        // if (env('APP_NOTIFICATION')) {
        $patient->notify(new SystemNotification(
            title: 'مكالمة فيديو واردة',
            message: 'طبيبك يتصل بك لموعدك.',
            data: collect(['event' => 'call_invitation'])
                ->merge($this->stringifyArray($videoCallData))
                ->all()
        ));
        // }

        return $this->successResponse(new VideoCallResource($videoCall));
    }

    private function stringifyArray(array $array, string $prefix = '')
    {
        $result = [];

        foreach ($array as $key => $value) {
            $fullKey = $prefix ? "{$prefix}_{$key}" : $key;

            if (is_array($value)) {
                $result += $this->stringifyArray($value, $fullKey);
            } elseif (is_object($value)) {
                $result[$fullKey] = json_encode($value);
            } else {
                $result[$fullKey] = (string) $value;
            }
        }

        return $result;
    }
}
