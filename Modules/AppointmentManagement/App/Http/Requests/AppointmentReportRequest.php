<?php

namespace Modules\AppointmentManagement\App\Http\Requests;

use App\Http\Requests\BaseRequest;

class AppointmentReportRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'diagnosis' => ['required', 'string'],
            'prescription' => ['required', 'string'],
            'additional_notes' => ['nullable', 'string'],
        ];
    }
}
