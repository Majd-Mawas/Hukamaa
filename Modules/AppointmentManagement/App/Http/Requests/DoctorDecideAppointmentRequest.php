<?php

namespace Modules\AppointmentManagement\App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class DoctorDecideAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action' => ['required', 'in:accept,reject'],
        ];
    }
}
