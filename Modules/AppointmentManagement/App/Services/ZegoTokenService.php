<?php

namespace Modules\AppointmentManagement\App\Services;

use Illuminate\Support\Str;
use Carbon\Carbon;

class ZegoTokenService
{
    protected int $appId;
    protected string $serverSecret;

    public function __construct()
    {
        $this->appId = (int) config('services.zegocloud.app_id');
        $this->serverSecret = config('services.zegocloud.server_secret');
    }

    public function generateToken(string $userId, int $validSeconds = 3600): string
    {
        $expire = Carbon::now()->addSeconds($validSeconds)->timestamp;
        $nonce = random_int(1, 999999);

        $payload = [
            "app_id" => $this->appId,
            "user_id" => $userId,
            "nonce" => $nonce,
            "expired" => $expire,
        ];

        $raw = json_encode($payload);
        $hash = hash_hmac('sha256', $raw, $this->serverSecret, true);
        $token = base64_encode($raw . $hash);

        return $token;
    }

    public function createRoomId(): string
    {
        return 'room-' . Str::uuid();
    }
}
