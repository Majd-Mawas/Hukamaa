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

function sendDataMessage(string $fcmToken, array $data): void
{
    $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials_file'));

    $messaging = $factory->createMessaging();

    $messaging->send([
        'token' => $fcmToken,
        'data' => $data,
    ]);
}
