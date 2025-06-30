<?php

namespace Modules\DoctorManagement\App\Http\Resources;

use App\Http\Resources\ApiResource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AvailabilityResource extends ApiResource
{
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
            'doctor_id' => $this->doctor_id,
            'weekday' => $this->weekday,
            'start_time' => Carbon::parse($this->start_time)->format('H:i'),
            'end_time' => Carbon::parse($this->end_time)->format('H:i'),
            'doctor' => $this->whenLoaded('doctor', function () {
                return [
                    'id' => $this->doctor->id,
                    'name' => $this->doctor->user->name,
                ];
            }),
        ];
    }
}
