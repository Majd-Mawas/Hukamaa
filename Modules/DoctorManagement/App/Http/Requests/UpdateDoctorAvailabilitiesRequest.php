<?php

namespace Modules\DoctorManagement\App\Http\Requests;

use App\Http\Requests\BaseRequest;

class UpdateDoctorAvailabilitiesRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'doctor';
    }

    public function rules(): array
    {
        return [
            // Availability
            'availabilities' => ['required', 'array', 'min:1'],
            'availabilities.*.weekday' => ['required', 'integer', 'between:0,6'],
            'availabilities.*.start_time' => ['required', 'date_format:H:i'],
            'availabilities.*.end_time' => ['required', 'date_format:H:i', 'after:availabilities.*.start_time'],
        ];
    }
}
