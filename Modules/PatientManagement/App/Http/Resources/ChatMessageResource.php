<?php

namespace Modules\PatientManagement\App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\UserManagement\App\Http\Resources\UserResource;

class ChatMessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sender' => new UserResource($this->sender),
            'receiver' => new UserResource($this->receiver),
            'message' => $this->message,
            'sent_at' => $this->getFormattedSentAt(),
            'read_at' => $this->read_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function getFormattedSentAt()
    {
        if (!$this->sent_at) {
            return null;
        }

        $userTimezone = Auth::user()->timezone ?? config('app.timezone');

        return Carbon::parse($this->sent_at)
            ->setTimezone($userTimezone)
            ->toIso8601String();
    }
}
