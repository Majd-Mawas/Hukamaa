<?php

namespace Modules\DoctorManagement\App\Http\Resources;

use App\Http\Resources\ApiResource;
use Illuminate\Http\Request;

class DoctorProfileResource extends ApiResource
{
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
            'user_id' => $this->user_id,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'profile_picture' => $this->profile_picture,
            'specialization_id' => $this->specialization_id,
            'consultation_fee' => $this->consultation_fee,
            'title' => $this->title,
            'experience_years' => $this->experience_years,
            'experience_description' => $this->experience_description,
            'certificates' => $this->certificates,
            'status' => $this->status,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
            'specialization' => $this->whenLoaded('specialization', function () {
                return [
                    'id' => $this->specialization->id,
                    'name' => $this->specialization->name,
                ];
            }),
            'availabilities' => $this->whenLoaded('availabilities', function () {
                return $this->availabilities->map(function ($availability) {
                    return [
                        'id' => $availability->id,
                        'weekday' => $availability->weekday,
                        'start_time' => $availability->start_time->format('H:i'),
                        'end_time' => $availability->end_time->format('H:i'),
                    ];
                });
            }),
        ];
    }
}
