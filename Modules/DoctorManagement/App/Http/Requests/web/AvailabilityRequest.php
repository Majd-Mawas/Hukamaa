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

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        if (isset($validated['start_time'])) {
            $validated['start_time'] = date('H:i:s', strtotime($validated['start_time']));
        }

        if (isset($validated['end_time'])) {
            $validated['end_time'] = date('H:i:s', strtotime($validated['end_time']));
        }

        return $validated;
    }
}
