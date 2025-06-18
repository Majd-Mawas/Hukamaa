<?php

namespace Modules\DoctorManagement\App\Http\Requests;

use App\Http\Requests\BaseRequest;
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
            'name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'string', Rule::in(['male', 'female'])],
            'phone_number' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'profile_picture' => ['nullable', 'file', 'image', 'max:5120'],

            // Coverage Areas
            'coverage_areas' => ['required', 'array', 'min:1'],
            'coverage_areas.*' => ['required', 'exists:coverage_areas,id'],

            'services' => ['required', 'array'],
            'services.*' => ['required', 'string', 'in:remote_video_consultation,home_visit'],
        ];
    }
}
