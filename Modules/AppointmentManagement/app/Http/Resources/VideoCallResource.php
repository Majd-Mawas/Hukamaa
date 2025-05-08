<?php

namespace Modules\AppointmentManagement\App\Http\Resources;

use App\Http\Resources\ApiResource;
use Illuminate\Http\Request;

class VideoCallResource extends ApiResource
{
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
            'appointment_id' => $this->appointment_id,
            'started_at' => $this->started_at?->toISOString(),
            'ended_at' => $this->ended_at?->toISOString(),
            'call_duration' => $this->call_duration,
            'status' => $this->status,
            'appointment' => $this->whenLoaded('appointment', function () {
                return [
                    'id' => $this->appointment->id,
                    'date' => $this->appointment->date->format('Y-m-d'),
                    'time_slot' => $this->appointment->time_slot,
                ];
            }),
        ];
    }
}
