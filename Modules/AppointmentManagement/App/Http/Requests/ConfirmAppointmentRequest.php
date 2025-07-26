<?php

namespace Modules\AppointmentManagement\App\Http\Requests;

use App\Http\Requests\BaseRequest;

class ConfirmAppointmentRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            // 'date' => ['required', 'date', 'after:today'],
            'date' => ['required', 'date'],
            'doctor_id' => ['required', 'exists:doctor_profiles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'start_time.required' => 'Please provide a start time',
            'start_time.date_format' => 'Invalid start time format',
            'end_time.required' => 'Please provide an end time',
            'end_time.date_format' => 'Invalid end time format',
            'end_time.after' => 'End time must be after start time',
            'date.required' => 'Please provide a date',
            'date.date' => 'Invalid date format',
            'date.after' => 'Date must be in the future',
        ];
    }
}
