<?php

namespace Modules\UserManagement\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
    public function attributes(): array
    {
        return trans('usermanagement::validation.attributes');
    }

    public function messages(): array
    {
        return [
            'email.required' => trans('usermanagement::validation.custom.email.required'),
            'email.email' => trans('usermanagement::validation.custom.email.email'),
            'email.exists' => trans('usermanagement::validation.custom.email.exists'),
            'token.required' => trans('usermanagement::validation.custom.token.required'),
            'token.string' => trans('usermanagement::validation.custom.token.string'),
            'password.required' => trans('usermanagement::validation.custom.password.required'),
            'password.min' => trans('usermanagement::validation.custom.password.min'),
            'password.confirmed' => trans('usermanagement::validation.custom.password.confirmed'),
        ];
    }
}
