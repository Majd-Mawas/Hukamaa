<?php

namespace Modules\UserManagement\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'code' => ['required', 'string', 'size:6', 'regex:/^[0-9]+$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'The verification code is required',
            'code.size' => 'The verification code must be exactly 6 digits',
            'code.regex' => 'The verification code must contain only numbers',
        ];
    }
}
