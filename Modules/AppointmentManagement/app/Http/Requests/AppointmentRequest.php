<?php

namespace Modules\AppointmentManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'exists:users,id'],
            'doctor_id' => ['required', 'exists:users,id'],
            'date' => ['required', 'date', 'after:today'],
            'time_slot' => ['required', 'string'],
            'status' => ['sometimes', 'string', 'in:scheduled,completed,cancelled'],
            'confirmed_by_doctor' => ['sometimes', 'boolean'],
            'confirmed_by_patient' => ['sometimes', 'boolean'],
        ];
    }
}
