<?php

namespace Modules\PatientManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BasicInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'patient';
    }

    public function rules(): array
    {
        return [
            'gender' => ['required', 'string', 'in:male,female,other'],
            'birth_date' => ['required', 'date', 'before:today'],
            'medical_history' => ['nullable', 'string'],
            'chronic_conditions' => ['nullable', 'string'],
        ];
    }
}
