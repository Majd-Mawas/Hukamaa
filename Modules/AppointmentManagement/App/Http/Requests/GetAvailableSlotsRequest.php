<?php

namespace Modules\AppointmentManagement\App\Http\Requests;

use App\Http\Requests\BaseRequest;

class GetAvailableSlotsRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'doctor_id' => ['required', 'exists:doctor_profiles,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'doctor_id.required' => 'Please select a doctor',
            'doctor_id.exists' => 'The selected doctor is invalid',
            'date.required' => 'Please select a date',
            'date.after_or_equal' => 'The date must be today or in the future',
        ];
    }
}
