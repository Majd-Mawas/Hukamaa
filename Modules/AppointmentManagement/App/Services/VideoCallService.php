<?php

namespace Modules\AppointmentManagement\App\Services;

use Modules\AppointmentManagement\App\Enums\AppointmentStatus;
use Modules\AppointmentManagement\App\Models\Appointment;
use Modules\AppointmentManagement\App\Models\VideoCall;

class VideoCallService
{
    public function createVideoCall(array $data): VideoCall
    {
        return VideoCall::create($data);
    }

    public function updateVideoCall(VideoCall $videoCall, array $data): VideoCall
    {
        $videoCall->update($data);
        return $videoCall->fresh();
    }

    public function endVideoCall(Appointment $appointment): VideoCall
    {
        $videoCall = $appointment->videoCall;

        if (!$videoCall) {
            $videoCall = VideoCall::create([
                'appointment_id' => $appointment->id,
                'status' => 'completed',
                'started_at' => now(),
                'ended_at' => now(),
            ]);
        } else {
            $data = [
                'ended_at' => now(),
                'status' => 'completed'
            ];
            if ($videoCall->started_at) {
                $data['call_duration'] = $videoCall->started_at->diffInSeconds(now());
            } else {
                $data['status'] = 'failed';
            }

            $videoCall->update($data);
        }

        $appointment->update([
            'status' => AppointmentStatus::COMPLETED->value
        ]);

        return $videoCall->fresh();
    }
}
