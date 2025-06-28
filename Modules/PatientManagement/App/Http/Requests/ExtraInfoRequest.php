<?php

namespace Modules\PatientManagement\App\Http\Requests;

use App\Http\Requests\BaseRequest;

class ExtraInfoRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'patient';
    }

    public function rules(): array
    {
        return [
            // 'allergies' => ['nullable', 'string'],
            'current_medications' => ['nullable', 'string'],
            'files.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // 10MB max,
            'allergies' => ['required', 'array'],
            'allergies.*' => ['required', 'integer', 'exists:allergies,id']
        ];
    }
}
