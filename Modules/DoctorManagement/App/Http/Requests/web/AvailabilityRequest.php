<?php

namespace Modules\DoctorManagement\App\Http\Requests\web;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class AvailabilityRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'weekday' => ['nullable', Rule::in(
                array_map(fn($case) => $case->value, \Modules\DoctorManagement\App\Enums\Weekday::cases())
            )],
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ];
    }
}
