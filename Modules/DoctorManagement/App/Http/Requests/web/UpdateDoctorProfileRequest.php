<?php

namespace Modules\DoctorManagement\App\Http\Requests\web;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateDoctorProfileRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'specialization' => ['required', 'exists:specializations,id'],
            'experience_years' => ['required', 'integer', 'min:0'],
            'profile_picture' => ['nullable', 'image', 'max:2048']
        ];
    }
}
