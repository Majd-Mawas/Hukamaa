<?php

namespace App\Traits;

use App\Services\TimezoneService;
use Carbon\Carbon;

trait HasTimezoneAwareTimes
{
    /**
     * Get start time in user's timezone
     */
    public function getStartTimeInTimezone(?string $userTimezone = null): string
    {
        $timezoneService = app(TimezoneService::class);

        return $timezoneService->convertToUserTimezone(
            $this->start_time,
            $userTimezone,
            $this->date?->format('Y-m-d')
        );
    }

    /**
     * Get end time in user's timezone
     */
    public function getEndTimeInTimezone(?string $userTimezone = null): string
    {
        $timezoneService = app(TimezoneService::class);

        return $timezoneService->convertToUserTimezone(
            $this->end_time,
            $userTimezone,
            $this->date?->format('Y-m-d')
        );
    }

    /**
     * Get formatted time range in user's timezone
     */
    public function getTimeRangeInTimezone(?string $userTimezone = null, ?string $patientTimezone = null): array
    {
        $timezoneService = app(TimezoneService::class);

        return $timezoneService->formatAppointmentTimeRange(
            $this->start_time,
            $this->end_time,
            $userTimezone,
            $patientTimezone,
            $this->date?->format('Y-m-d')
        );
    }

    /**
     * Get appointment datetime in user's timezone
     */
    public function getAppointmentDateTimeInTimezone(?string $userTimezone = null): Carbon
    {
        $timezoneService = app(TimezoneService::class);
        $userTimezone = $userTimezone ?? $timezoneService->getUserTimezone();

        try {
            $dateTime = $this->date->format('Y-m-d') . ' ' . $this->start_time;
            return Carbon::createFromFormat('Y-m-d H:i:s', $dateTime, 'UTC')
                ->setTimezone($userTimezone);
        } catch (\Exception $e) {
            return Carbon::parse($this->date)->setTimezone($userTimezone);
        }
    }
}
