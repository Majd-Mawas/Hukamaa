<?php

namespace Modules\DoctorManagement\App\Http\Requests;

use App\Http\Requests\BaseRequest;
use App\Rules\ArabicNumeric;

class DoctorProfileRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'string', 'in:male,female'],
            'profile_picture' => ['nullable', 'string', 'max:255'],
            'specialization_id' => ['required', 'exists:specializations,id'],
            'consultation_fee' => ['required', 'numeric', 'min:0', 'max:10000'],
            'title' => ['required', 'string', 'max:255'],
            'experience_years' => ['required', new ArabicNumeric(), 'min:0', 'max:50'],
            'experience_description' => ['required', 'string'],
            'certificates' => ['required', 'array'],
            'certificates.*' => ['required', 'string'],
            'status' => ['sometimes', 'string', 'in:pending,approved,rejected'],
        ];
    }
}
