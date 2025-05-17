<?php

namespace Modules\AppointmentManagement\App\Http\Resources;

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
            'date' => $this->date?->format('Y-m-d'),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status' => $this->status ? trans('appointmentmanagement::appointments.status.' . $this->status->value) : null,
            'confirmed_by_doctor' => $this->confirmed_by_doctor,
            'confirmed_by_patient' => $this->confirmed_by_patient,
            'condition_description' => $this->condition_description,
            'patient' => $this->whenLoaded('patient', function () {
                return [
                    'id' => $this->patient->id,
                    'name' => $this->patient->name,
                ];
            }),
            'doctor' => $this->whenLoaded('doctor', function () {
                return [
                    'id' => $this->doctor->id,
                    'name' => $this->doctor->name,
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
            'files' => collect()
                ->concat($this->getMedia('appointment_files'))
                ->concat([$this->getFirstMedia('payment_invoices')])
                ->filter()
                ->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'name' => $media->file_name,
                        'mime_type' => $media->mime_type,
                        'size' => $media->size,
                        'url' => $media->getUrl(),
                        'collection' => $media->collection_name,
                    ];
                }),
        ];
    }
}
