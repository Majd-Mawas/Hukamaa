<?php

namespace Modules\PatientManagement\App\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use Modules\DoctorManagement\App\Enums\UserRole;
use Modules\UserManagement\App\Models\User;

class StartChatRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:1000'],
        ];
    }
}
