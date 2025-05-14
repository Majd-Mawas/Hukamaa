<?php

namespace Modules\AppointmentManagement\App\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class CreateAppointmentRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'doctor_id' => ['required', 'exists:doctor_profiles,id'],
            'date' => ['nullable', 'date', 'after:today'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'condition_description' => ['required', 'string', 'max:1000'],
            'files.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // 10MB max
        ];
    }

    public function messages(): array
    {
        return [
            'doctor_id.required' => 'Please select a doctor',
            'doctor_id.exists' => 'The selected doctor is invalid',
            'date.required' => 'Please select a date',
            'date.after' => 'The appointment date must be in the future',
            'start_time.date_format' => 'Invalid start time format',
            'end_time.date_format' => 'Invalid end time format',
            'end_time.after' => 'End time must be after start time',
            'condition_description.required' => 'Please describe your condition',
            'condition_description.max' => 'Condition description cannot exceed 1000 characters',
            'files.*.mimes' => 'Files must be in PDF, JPG, JPEG, or PNG format',
            'files.*.max' => 'Each file must not exceed 10MB',
        ];
    }
}
