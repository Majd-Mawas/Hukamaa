<?php

namespace Modules\DoctorManagement\App\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class MedicalInfoRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'doctor';
    }

    public function rules(): array
    {
        $rules = [
            'specialization_id' => ['required', 'exists:specializations,id'],
            'title' => ['required', 'string', 'in:Dr.,Prof.,Assoc. Prof.,Asst. Prof.'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:50'],
            'experience_description' => ['required', 'string'],
            'services' => ['required', 'array'],
            'services.*' => ['required', 'string', 'in:remote_video_consultation,home_visit'],
        ];

        // Add conditional validation for coverage_area if home_visit is selected
        // if ($this->has('services') && in_array('home_visit', $this->input('services', []))) {
        //     $rules['coverage_area'] = ['required', 'string'];
        // }

        return $rules;
    }
}
