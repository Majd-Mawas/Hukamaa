<?php

use Kreait\Firebase\Factory;
use Modules\AdminPanel\App\Models\Setting;
use Modules\UserManagement\App\Models\User;

function getSetting(string $key, $default = null)
{
    return Setting::where('key', $key)->value('value') ?? $default;
}

function updateSetting(string $key, $value): void
{
    Setting::updateOrCreate(
        ['key' => $key],
        ['value' => $value]
    );
}

function getAdminUser()
{
    return User::where('role', 'admin')->first();
}

function getAdminUsers()
{
    return User::where('role', 'admin')->get();
}

function sendDataMessage(?string $fcmToken, array $data): void
{
    $sanitizedData = array_map(fn($v) => is_array($v) ? json_encode($v) : (string)$v, $data);

    try {
        $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials_file'));
        $messaging = $factory->createMessaging();

        $messaging->send([
            'token' => $fcmToken,
            'data' => $sanitizedData,
        ]);
    } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
        \Log::warning("FCM token not found. Removing token.", [
            'token' => $fcmToken,
            'error' => $e->getMessage(),
        ]);

        \DB::table('users')->where('fcm_token', $fcmToken)->update(['fcm_token' => null]);
    } catch (\Throwable $e) {
        \Log::error("FCM sending failed", [
            'token' => $fcmToken,
            'error' => $e->getMessage(),
        ]);
    }
}
