<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TimezoneService
{
    /**
     * Convert time from UTC to user's timezone
     */
    public function convertToUserTimezone(string $time, ?string $userTimezone = null, ?string $patientTimezone = null, ?string $date = null): string
    {
        $userTimezone = $userTimezone ?? $this->getUserTimezone();

        $date = $date ?? now()->format('Y-m-d');

        try {
            $carbonTime = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $time, $patientTimezone);

            return $carbonTime->setTimezone($userTimezone)->format('H:i:s');
        } catch (\Exception $e) {
            return $time;
        }
    }

    public function convertToUtc(string $time, ?string $userTimezone = null, ?string $date = null): string
    {
        $userTimezone = $userTimezone ?? $this->getUserTimezone();
        $date = $date ?? now()->format('Y-m-d');

        try {
            $carbonTime = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $time, $userTimezone);
            return $carbonTime->utc()->format('H:i:s');
        } catch (\Exception $e) {
            return $time;
        }
    }

    public function convertDateTimeToUserTimezone(string $datetime, ?string $userTimezone = null): Carbon
    {
        $userTimezone = $userTimezone ?? $this->getUserTimezone();

        try {
            return Carbon::parse($datetime)->setTimezone($userTimezone);
        } catch (\Exception $e) {
            return Carbon::parse($datetime);
        }
    }

    public function getUserTimezone(): string
    {
        $user = Auth::user();

        if ($user && !empty($user->timezone)) {
            return $user->timezone;
        }

        return 'UTC';
    }

    public function getUserTimezoneById(int $userId): string
    {
        $user = \Modules\UserManagement\App\Models\User::find($userId);

        if ($user && !empty($user->timezone)) {
            return $user->timezone;
        }

        return 'UTC';
    }

    public function formatAppointmentTimeRange(string $startTime, string $endTime, ?string $userTimezone = null, ?string $patientTimezone = null, ?string $date = null): array
    {
        $userTimezone = $userTimezone ?? $this->getUserTimezone();

        return [
            'start_time' => $this->convertToUserTimezone($startTime, $userTimezone, $patientTimezone, $date),
            'end_time' => $this->convertToUserTimezone($endTime, $userTimezone, $patientTimezone, $date),
            'timezone' => $userTimezone,
            'formatted_range' => $this->getFormattedTimeRange($startTime, $endTime, $userTimezone, $date)
        ];
    }

    private function getFormattedTimeRange(string $startTime, string $endTime, string $userTimezone, ?string $date = null): string
    {
        $date = $date ?? now()->format('Y-m-d');

        try {
            $startCarbon = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $startTime, 'UTC')
                ->setTimezone($userTimezone);
            $endCarbon = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $endTime, 'UTC')
                ->setTimezone($userTimezone);

            return $startCarbon->format('h:i A') . ' - ' . $endCarbon->format('h:i A');
        } catch (\Exception $e) {
            return $startTime . ' - ' . $endTime;
        }
    }

    public function isValidTimezone(string $timezone): bool
    {
        try {
            new \DateTimeZone($timezone);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getCommonTimezones(): array
    {
        return [
            'UTC' => 'UTC',
            'America/New_York' => 'Eastern Time (US & Canada)',
            'America/Chicago' => 'Central Time (US & Canada)',
            'America/Denver' => 'Mountain Time (US & Canada)',
            'America/Los_Angeles' => 'Pacific Time (US & Canada)',
            'Europe/London' => 'London',
            'Europe/Paris' => 'Paris',
            'Europe/Berlin' => 'Berlin',
            'Asia/Tokyo' => 'Tokyo',
            'Asia/Shanghai' => 'Shanghai',
            'Asia/Dubai' => 'Dubai',
            'Asia/Riyadh' => 'Riyadh',
            'Asia/Kuwait' => 'Kuwait',
            'Asia/Qatar' => 'Qatar',
            'Australia/Sydney' => 'Sydney',
        ];
    }

    public function convertTimeBetweenTimezones(string $time, string $fromTimezone, string $toTimezone, ?string $date = null): string
    {
        $date = $date ?? now()->format('Y-m-d');

        try {
            $carbonTime = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $time, $fromTimezone);

            return $carbonTime->setTimezone($toTimezone)->format('H:i:s');
        } catch (\Exception $e) {
            return $time;
        }
    }
}
