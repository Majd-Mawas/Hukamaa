<?php

namespace Modules\DoctorManagement\App\Http\Resources;

use App\Http\Resources\ApiResource;
use Illuminate\Http\Request;

class AvailabilityResource extends ApiResource
{
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
            'doctor_id' => $this->doctor_id,
            'weekday' => $this->weekday,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'doctor' => $this->whenLoaded('doctor', function () {
                return [
                    'id' => $this->doctor->id,
                    'name' => $this->doctor->user->name,
                ];
            }),
        ];
    }
}
