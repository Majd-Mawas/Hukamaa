<?php

namespace Modules\PatientManagement\App\Http\Resources;

use App\Http\Resources\ApiResource;
use Illuminate\Http\Request;

class PatientProfileResource extends ApiResource
{
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
            'user_id' => $this->user_id,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date?->format('Y-m-d'),
            'medical_history' => $this->medical_history,
            'current_medications' => $this->current_medications,
            'is_profile_complete' => $this->is_profile_complete,
            'files' => $this->whenLoaded('media', function () {
                return $this->getMedia('patient_files')->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'url' => $media->getUrl(),
                        'name' => $media->file_name,
                        'mime_type' => $media->mime_type,
                        'size' => $media->size,
                    ];
                });
            }),
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
            'allergies' => AllergyResource::collection($this->allergies),
            'chronic_conditions' => ChronicConditionResource::collection($this->chronicConditions),
        ];
    }
}
