<?php

namespace Modules\PatientManagement\App\Http\Requests;

use App\Http\Requests\BaseRequest;

class BasicInfoRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'patient';
    }

    public function rules(): array
    {
        return [
            'gender' => ['required', 'string', 'in:male,female'],
            'birth_date' => ['required', 'date', 'before:today'],
            'medical_history' => ['nullable', 'string'],
            'chronic_conditions' => ['nullable', 'string'],
        ];
    }
}
