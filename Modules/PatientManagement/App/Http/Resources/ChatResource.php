<?php

namespace Modules\PatientManagement\App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\UserManagement\App\Http\Resources\UserResource;

class ChatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'latest_message' => $this->when($this->latest_message, new ChatMessageResource($this->latest_message)),
            'unread_count' => $this->when(isset($this->unread_count), $this->unread_count),
            'profile' => $this->when($this->role === 'patient', function () {
                return $this->patientProfile;
            }, function () {
                return $this->doctorProfile;
            }),
        ];
    }
}