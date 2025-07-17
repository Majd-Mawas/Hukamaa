<?php

namespace Modules\PatientManagement\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            // 'allergies' => 'nullable|string|max:1000',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
            'allergies' => ['required', 'array'],
            'allergies.*' => ['required', 'integer', 'exists:allergies,id'],
            'chronic_conditions' => ['required', 'array'],
            'chronic_conditions.*' => ['required', 'integer', 'exists:chronic_conditions,id'],
            'medical_history' => ['nullable', 'string'],
            'current_medications' => ['nullable', 'string'],
            'phone_number' => ['nullable', 'string', 'max:20'],
        ];
    }
}
