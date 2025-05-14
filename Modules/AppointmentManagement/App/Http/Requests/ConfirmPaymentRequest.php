<?php

namespace Modules\AppointmentManagement\App\Http\Requests;

use App\Http\Requests\BaseRequest;

class ConfirmPaymentRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice_file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // 10MB max
        ];
    }

    public function messages(): array
    {
        return [
            'invoice_file.required' => 'Please upload the payment invoice',
            'invoice_file.mimes' => 'Invoice must be in PDF, JPG, JPEG, or PNG format',
            'invoice_file.max' => 'Invoice file must not exceed 10MB',
        ];
    }
}
