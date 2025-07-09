<?php

namespace Modules\PatientManagement\App\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use Modules\UserManagement\App\Models\User;

class SendMessageRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            // 'receiver_id' => [
            //     'required',
            //     'integer',
            //     Rule::exists(User::class, 'id'),
            //     function ($attribute, $value, $fail) {
            //         if ($value == $this->user()->id) {
            //             $fail('You cannot send a message to yourself.');
            //         }
            //     },
            // ],
            'message' => ['required', 'string', 'max:1000'],
        ];
    }
}
