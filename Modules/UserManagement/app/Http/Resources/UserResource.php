<?php

namespace Modules\UserManagement\Http\Resources;

use App\Http\Resources\ApiResource;
use Illuminate\Http\Request;

class UserResource extends ApiResource
{
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'email_verified_at' => $this->email_verified_at?->toISOString(),
        ];
    }
}
