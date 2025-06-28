<?php

use Modules\AdminPanel\App\Models\Setting;

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
