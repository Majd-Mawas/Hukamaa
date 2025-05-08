<?php

namespace Modules\DoctorManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'age' => ['required', 'integer', 'min:18', 'max:100'],
            'sex' => ['required', 'string', 'in:male,female,other'],
            'profile_picture' => ['nullable', 'string', 'max:255'],
            'specialization_id' => ['required', 'exists:specializations,id'],
            'title' => ['required', 'string', 'max:255'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:50'],
            'experience_description' => ['required', 'string'],
            'certificates' => ['required', 'array'],
            'certificates.*' => ['required', 'string'],
            'status' => ['sometimes', 'string', 'in:pending,approved,rejected'],
        ];
    }
}
