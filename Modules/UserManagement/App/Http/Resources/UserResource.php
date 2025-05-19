<?php

namespace Modules\UserManagement\App\Http\Resources;

use App\Http\Resources\ApiResource;
use Illuminate\Http\Request;
use Modules\DoctorManagement\App\Enums\UserRole;
use Modules\PatientManagement\App\Http\Resources\PatientProfileResource;
use Modules\DoctorManagement\App\Http\Resources\DoctorProfileResource;

class UserResource extends ApiResource
{
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'email_verified_at' => $this->email_verified_at?->toISOString(),
            'profile' => $this->when($this->role === UserRole::PATIENT->value, function () {
                return $this->whenLoaded('patientProfile', function () {
                    return new PatientProfileResource($this->patientProfile->load('media'));
                });
            }, function () {
                return $this->whenLoaded('doctorProfile', function () {
                    return new DoctorProfileResource($this->doctorProfile->load('media', 'specialization'));
                });
            }),
        ];
    }
}
