<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

abstract class BaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        logger()->info($validator->errors());

        $errorString = collect($validator->errors()->all())->implode(',\n ');

        throw new ValidationException($validator, response()->json([
            'success' => false,
            // 'message' => 'Validation failed',
            'message' => $errorString,
            'errors' => $validator->errors(),
            'errors_string' => $errorString
        ], 422));
    }
}
