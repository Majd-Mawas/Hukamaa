<?php

namespace Modules\DoctorManagement\App\Http\Requests;

use App\Http\Requests\BaseRequest;
use App\Rules\ArabicNumeric;
use Illuminate\Validation\Rule;

class UpdateDoctorProfileRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'doctor';
    }

    public function rules(): array
    {
        return [
            // Personal Information
            'name' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'string', Rule::in(['male', 'female'])],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'profile_picture' => ['nullable', 'file', 'image', 'max:5120'],

            'coverage_areas' => ['nullable', 'array', 'min:1'],
            'coverage_areas.*' => ['nullable', 'exists:coverage_areas,id'],

            'services' => ['nullable', 'array'],
            'services.*' => ['nullable', 'string', 'in:remote_video_consultation,home_visit'],

            'experience_description' => ['nullable', 'string'],
            'experience_years' => ['required', new ArabicNumeric(), 'min:0', 'max:50'],

            'practice_license' => ['nullable', 'array'],
            'practice_license.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],

            'medical_certificates' => ['nullable', 'array'],
            'medical_certificates.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],

            'expertise_focus' => ['nullable', 'string'],

        ];
    }
}
