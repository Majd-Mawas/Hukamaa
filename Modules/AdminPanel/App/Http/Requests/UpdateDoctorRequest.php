<?php

namespace Modules\AdminPanel\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->doctor->user_id)],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'in:male,female,other'],
            'phone_number' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'specialization_id' => ['required', 'exists:specializations,id'],
            'consultation_fee' => ['required', 'numeric', 'min:0'],
            'title' => ['required', 'string', 'max:255'],
            'experience_years' => ['required', 'integer', 'min:0'],
            'experience_description' => ['required', 'string'],
            'services' => ['required', 'array'],
            'coverage_area' => ['required', 'string'],
            'expertise_focus' => ['required', 'string'],
            'profile_picture' => ['nullable', 'image', 'max:2048'],
            'certificates.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048']
        ];
    }
}
