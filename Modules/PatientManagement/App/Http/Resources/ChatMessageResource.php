<?php

namespace Modules\PatientManagement\App\Http\Resources;

use App\Services\TimezoneService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\UserManagement\App\Http\Resources\UserResource;

class ChatMessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $timezoneService = app(TimezoneService::class);

        return [
            'id' => $this->id,
            'sender' => new UserResource($this->sender),
            'receiver' => new UserResource($this->receiver),
            'message' => $this->message,
            'sent_at' => $this->sent_at ? $timezoneService->convertDateTimeToUserTimezone($this->sent_at) : null,
            'read_at' => $this->read_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
