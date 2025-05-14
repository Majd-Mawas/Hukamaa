<?php

namespace Modules\SpecializationManagement\App\Http\Requests;

use App\Http\Requests\BaseRequest;

class SpecializationRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'department_name' => ['required', 'string', 'max:255'],
            'specialization_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'department_name.required' => 'The department name is required.',
            'department_name.max' => 'The department name must not exceed 255 characters.',
            'specialization_name.required' => 'The specialization name is required.',
            'specialization_name.max' => 'The specialization name must not exceed 255 characters.',
            'description.string' => 'The description must be a string.',
        ];
    }
}
