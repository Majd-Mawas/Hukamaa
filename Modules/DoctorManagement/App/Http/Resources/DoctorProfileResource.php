<?php

namespace Modules\DoctorManagement\App\Http\Resources;

use App\Http\Resources\ApiResource;
use Carbon\Carbon;
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
            'specialization_id' => $this->specialization_id,
            'consultation_fee' => $this->consultation_fee,
            'title' => $this->title,
            'experience_years' => $this->experience_years,
            'experience_description' => $this->experience_description,
            'status' => $this->status,
            'services' => $this->services,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'files' => $this->whenLoaded('media', function () {
                return [
                    'practice_license' => $this->getMedia('practice_license')->map(function ($media) {
                        return [
                            'id' => $media->id,
                            'url' => $media->getUrl(),
                            'name' => $media->file_name,
                            'mime_type' => $media->mime_type,
                            'size' => $media->size,
                        ];
                    })->first(),
                    'medical_certificates' => $this->getMedia('medical_certificates')->map(function ($media) {
                        return [
                            'id' => $media->id,
                            'url' => $media->getUrl(),
                            'name' => $media->file_name,
                            'mime_type' => $media->mime_type,
                            'size' => $media->size,
                        ];
                    }),
                    'identity_document' => $this->getMedia('identity_document')->map(function ($media) {
                        return [
                            'id' => $media->id,
                            'url' => $media->getUrl(),
                            'name' => $media->file_name,
                            'mime_type' => $media->mime_type,
                            'size' => $media->size,
                        ];
                    })->first(),
                    'profile_picture' =>  $this->getMedia('profile_picture')->map(function ($media) {
                        return [
                            'id' => $media->id,
                            'url' => $media->getUrl(),
                            'name' => $media->file_name,
                            'mime_type' => $media->mime_type,
                            'size' => $media->size,
                        ];
                    })->first(),
                ];
            }),
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
                    'name' => $this->specialization->specialization_name,
                    'description' => $this->specialization->description,
                ];
            }),
            'availabilities' => $this->whenLoaded('availabilities', function () {
                return $this->availabilities->map(function ($availability) {
                    return [
                        'id' => $availability->id,
                        'weekday' => $availability->weekday,
                        'start_time' => Carbon::parse($availability->start_time)->format('H:i'),
                        'end_time' => Carbon::parse($availability->end_time)->format('H:i'),
                    ];
                });
            }),
            'coverage_areas' => CoverageAreaResource::collection($this->coverageAreas),
            'stats' => [
                [
                    'title' => "عدد سنوات الخبرة",
                    'value' => $this->experience_years,
                    'icon' => asset('assets/images/icons/Experience.png')
                ],
                [
                    'title' => "كلفة الاستشارة",
                    'value' => $this->consultation_fee,
                    'icon' => asset('assets/images/icons/Fee.png')
                ],
                [
                    'title' => "عدد المرضى",
                    'value' => $this->whenCounted('patients'),
                    'icon' => asset('assets/images/icons/Patients.png')
                ],
            ],
        ];
    }
}
