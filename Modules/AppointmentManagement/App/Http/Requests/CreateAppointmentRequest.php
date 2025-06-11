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
            'condition_description' => ['required', 'string', 'max:1000'],
            'files.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            // 'schedule' => ['required', 'array'],
            // 'schedule.date' => ['required', 'date', 'after:today'],
            // 'schedule.start_time' => ['required', 'date_format:H:i'],
            // 'schedule.end_time' => ['required', 'date_format:H:i', 'after:schedule.start_time'],
        ];
    }

    public function messages(): array
    {
        return [
            'doctor_id.required' => 'Please select a doctor',
            'doctor_id.exists' => 'The selected doctor is invalid',
            'schedule.required' => 'Schedule information is required',
            'schedule.array' => 'Invalid schedule format',
            'schedule.date.required' => 'Please select a date',
            'schedule.date.date' => 'Invalid date format',
            'schedule.date.after' => 'The appointment date must be in the future',
            'schedule.start_time.required' => 'Please select a start time',
            'schedule.start_time.date_format' => 'Invalid start time format',
            'schedule.end_time.required' => 'Please select an end time',
            'schedule.end_time.date_format' => 'Invalid end time format',
            'schedule.end_time.after' => 'End time must be after start time',
            'condition_description.required' => 'Please describe your condition',
            'condition_description.string' => 'Condition description must be text',
            'condition_description.max' => 'Condition description cannot exceed 1000 characters',
            'files.*.file' => 'Invalid file upload',
            'files.*.mimes' => 'Files must be in PDF, JPG, JPEG, or PNG format',
            'files.*.max' => 'Each file must not exceed 10MB',
        ];
    }
}
