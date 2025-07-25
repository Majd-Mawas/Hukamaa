<?php

namespace Modules\AppointmentManagement\App\Http\Resources;

use App\Http\Resources\ApiResource;
use App\Services\TimezoneService;
use Illuminate\Http\Request;
use Modules\PatientManagement\App\Http\Resources\PatientProfileResource;
use Modules\UserManagement\App\Http\Resources\UserResource;

class AppointmentResource extends ApiResource
{
    public function toArray(Request $request): array
    {
        $timezoneService = app(TimezoneService::class);
        $userTimezone = $timezoneService->getUserTimezone();
        if ($this->start_time && $this->end_time) {
            $timeRange = $this?->getTimeRangeInTimezone($userTimezone, $this->patient->timezone) ?? null;
        }

        return [
            ...parent::toArray($request),
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'date' => $this->date?->format('Y-m-d'),

            // Timezone-aware times
            'start_time' => $timeRange['start_time'] ?? null,
            'end_time' => $timeRange['end_time'] ?? null,

            'status' => $this->status ? trans('appointmentmanagement::appointments.status.' . $this->status->value) : null,
            'confirmed_by_doctor' => $this->confirmed_by_doctor,
            'confirmed_by_patient' => $this->confirmed_by_patient,
            'condition_description' => $this->condition_description,
            'service' => $this->service,
            'patient' => $this->whenLoaded('patient', function () {
                return [
                    'id' => $this->patient->id,
                    'name' => $this->patient->name,
                    'profile' => new PatientProfileResource($this->patient->patientProfile->load('media'))
                ];
            }),
            'doctor' => $this->whenLoaded('doctor', function () {
                return new UserResource($this->doctor->load(['doctorProfile']));
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
            'report' => $this->whenLoaded('appointmentReport', function () {
                return [
                    'diagnosis' => $this->appointmentReport->diagnosis,
                    'prescription' => $this->appointmentReport->prescription,
                    'additional_notes' => $this->appointmentReport->additional_notes,
                    'created_at' => $this->appointmentReport->created_at?->toISOString(),
                    'updated_at' => $this->appointmentReport->updated_at?->toISOString(),
                ];
            }),
            'files' => collect()
                ->concat($this->getMedia('appointment_files'))
                // ->concat([$this->getFirstMedia('payment_invoices')])
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
