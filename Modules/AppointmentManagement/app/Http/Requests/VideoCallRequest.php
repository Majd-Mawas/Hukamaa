<?php

namespace Modules\AppointmentManagement\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VideoCallRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'appointment_id' => ['required', 'exists:appointments,id'],
            'started_at' => ['sometimes', 'date'],
            'ended_at' => ['sometimes', 'date', 'after:started_at'],
            'call_duration' => ['sometimes', 'integer', 'min:0'],
            'status' => ['sometimes', 'string', 'in:pending,in_progress,completed,failed'],
        ];
    }
}
