<?php

namespace Modules\DoctorManagement\App\Http\Requests;

use App\Http\Requests\BaseRequest;

class DocumentsRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'doctor';
    }

    public function rules(): array
    {
        return [
            'practice_license' => ['nullable', 'array'],
            'practice_license.*' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'medical_certificates' => ['nullable', 'array'],
            'medical_certificates.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'expertise_focus' => ['nullable', 'string'],
        ];
    }
}
