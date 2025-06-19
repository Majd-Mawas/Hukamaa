<?php

namespace Modules\AppointmentManagement\App\Http\Resources;

use App\Http\Resources\ApiResource;
use Illuminate\Http\Request;

class VideoCallResource extends ApiResource
{
    public function toArray(Request $request): array
    {
        $user = $request->user();
        $isDoctor = $user->id === $this->appointment->doctor_id;

        return [
            ...parent::toArray($request),
            'appointment_id' => $this->appointment_id,
            'started_at' => $this->started_at?->toISOString(),
            'ended_at' => $this->ended_at?->toISOString(),
            'call_duration' => $this->call_duration,
            'status' => $this->status,
            'room_id' => $this->room_id,
            'token' => $isDoctor ? $this->doctor_token : $this->patient_token,
            'user_id' => $isDoctor ? "doctor_{$user->id}" : "patient_{$user->id}",
            'app_id' => config('services.zegocloud.app_id'),
            'app_sign' => config('services.zegocloud.app_sign'),
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
