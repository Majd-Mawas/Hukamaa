<?php

namespace Modules\AppointmentManagement\Services;

use Modules\AppointmentManagement\Models\VideoCall;

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

    public function endVideoCall(VideoCall $videoCall): VideoCall
    {
        $videoCall->update([
            'ended_at' => now(),
            'call_duration' => $videoCall->started_at->diffInSeconds(now()),
            'status' => 'completed'
        ]);

        return $videoCall->fresh();
    }
}
