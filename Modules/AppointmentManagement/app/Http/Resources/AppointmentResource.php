<?php

namespace Modules\AppointmentManagement\Http\Resources;

use App\Http\Resources\ApiResource;
use Illuminate\Http\Request;

class AppointmentResource extends ApiResource
{
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'date' => $this->date->format('Y-m-d'),
            'time_slot' => $this->time_slot,
            'status' => $this->status,
            'confirmed_by_doctor' => $this->confirmed_by_doctor,
            'confirmed_by_patient' => $this->confirmed_by_patient,
            'patient' => $this->whenLoaded('patient', function () {
                return [
                    'id' => $this->patient->id,
                    'name' => $this->patient->user->name,
                ];
            }),
            'doctor' => $this->whenLoaded('doctor', function () {
                return [
                    'id' => $this->doctor->id,
                    'name' => $this->doctor->user->name,
                ];
            }),
            'video_call' => $this->whenLoaded('videoCall', function () {
                return [
                    'id' => $this->videoCall->id,
                    'started_at' => $this->videoCall->started_at?->toISOString(),
                    'ended_at' => $this->videoCall->ended_at?->toISOString(),
                    'call_duration' => $this->videoCall->call_duration,
                    'status' => $this->videoCall->status,
                ];
            }),
        ];
    }
}
