<?php

namespace Modules\DoctorManagement\App\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use Modules\DoctorManagement\App\Enums\Weekday;

class UpdateDoctorAvailabilitiesRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'doctor';
    }

    public function rules(): array
    {
        return [
            'availabilities' => ['required', 'array', 'min:1'],
            'availabilities.*.weekday' => [
                'required',
                'string',
                Rule::in(collect(Weekday::cases())->map(fn($day) => $day->value)),
            ],
            'availabilities.*.start_time' => ['required', 'date_format:H:i'],
            'availabilities.*.end_time' => [
                'required',
                'date_format:H:i',
            ],
        ];
    }
}
