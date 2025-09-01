<?php

namespace Modules\DoctorManagement\App\Http\Requests\web;

use App\Http\Requests\BaseRequest;
use App\Rules\ArabicNumeric;
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
            'experience_years' => ['required', new ArabicNumeric(), 'min:0', 'max:50'],
            'profile_picture' => ['nullable', 'image', 'max:2048']
        ];
    }
}
