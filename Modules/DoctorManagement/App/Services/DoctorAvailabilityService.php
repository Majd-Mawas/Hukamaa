<?php

namespace Modules\DoctorManagement\App\Services;

use Carbon\Carbon;
use Modules\DoctorManagement\App\Models\Availability;
use Modules\AppointmentManagement\App\Models\Appointment;
use Modules\DoctorManagement\App\Models\DoctorProfile;
use App\Services\TimezoneService;
use InvalidArgumentException;
use Modules\AppointmentManagement\App\Enums\AppointmentStatus;
use DateTimeZone;

class DoctorAvailabilityService
{
    private int $defaultSlotDuration = 30; // in minutes

    public function __construct(
        private ?int $slotDuration = null,
        private ?TimezoneService $timezoneService = null
    ) {
        $this->slotDuration = $slotDuration ?? $this->defaultSlotDuration;
        $this->timezoneService = $timezoneService ?? new TimezoneService();
    }

    /**
     * Get available appointment slots for a doctor on a specific date
     */
    public function getAvailableSlots(int $doctorProfileId, string $date, ?string $patientTimezone = null): array
    {
        $patientTimezone = $patientTimezone ?? $this->timezoneService->getUserTimezone();

        // Parse and validate the date
        $parsedDate = $this->parseDate($date);
        $date = $parsedDate->format('Y-m-d');
        $dayOfWeek = strtolower($parsedDate->format('l'));

        // Get doctor's availabilities for the day
        $availabilities = $this->getDoctorAvailabilities($doctorProfileId, $dayOfWeek);
        if ($availabilities->isEmpty()) {
            return [];
        }

        // Get doctor profile and timezone
        $doctorProfile = DoctorProfile::find($doctorProfileId);
        if (!$doctorProfile) {
            return [];
        }

        $doctorTimezone = $doctorProfile->user->timezone ?? 'UTC';

        // Calculate minimum start time (3 hours from now in doctor's timezone)
        $now = Carbon::now()->setTimezone($doctorTimezone);
        $minStartTime = $now->copy()->addHours(3);

        // Get doctor's appointments for the date
        $appointments = $this->getDoctorAppointments($doctorProfile->user_id, $date);

        // Generate available slots
        return $this->generateAvailableSlots(
            $availabilities,
            $date,
            $doctorTimezone,
            $patientTimezone,
            $minStartTime,
            $appointments,
            $parsedDate
        );
    }

    /**
     * Check if a specific slot is available
     */
    public function isSlotAvailable(int $doctorProfileId, string $date, string $startTime, string $endTime, ?string $patientTimezone = null): bool
    {
        $patientTimezone = $patientTimezone ?? $this->timezoneService->getUserTimezone();
        $availableSlots = $this->getAvailableSlots($doctorProfileId, $date);

        return in_array([
            'start_time' => Carbon::parse($startTime)->format('H:i'),
            'end_time' => Carbon::parse($endTime)->format('H:i')
        ], $availableSlots);
    }

    /**
     * Get available slots in UTC timezone
     */
    private function getAvailableSlotsInUtc(int $doctorProfileId, string $date): array
    {
        // Parse and validate the date
        $parsedDate = $this->parseDate($date);
        $date = $parsedDate->format('Y-m-d');
        $dayOfWeek = strtolower($parsedDate->format('l'));

        // Get doctor's availabilities for the day
        $availabilities = $this->getDoctorAvailabilities($doctorProfileId, $dayOfWeek);
        if ($availabilities->isEmpty()) {
            return [];
        }

        // Get doctor profile and timezone
        $doctorProfile = DoctorProfile::find($doctorProfileId);
        if (!$doctorProfile) {
            return [];
        }

        $doctorTimezone = $doctorProfile->user->timezone ?? 'UTC';

        // Get doctor's appointments for the date
        $appointments = $this->getDoctorAppointments($doctorProfile->user_id, $date);

        $slots = [];

        foreach ($availabilities as $availability) {
            // Create time-only Carbon instances in doctor's timezone
            $startTime = Carbon::createFromFormat('H:i:s', $availability->start_time, new DateTimeZone($doctorTimezone));
            $endTime = Carbon::createFromFormat('H:i:s', $availability->end_time, new DateTimeZone($doctorTimezone));

            // Set the date without altering the time
            $start = $startTime->setDateFrom(Carbon::parse($date, new DateTimeZone($doctorTimezone)));
            $end = $endTime->setDateFrom(Carbon::parse($date, new DateTimeZone($doctorTimezone)));

            while ($start->copy()->addMinutes($this->slotDuration)->lte($end)) {
                $slotStart = $start->copy();
                $slotEnd = $start->copy()->addMinutes($this->slotDuration);

                // Convert to UTC for conflict checking
                $slotStartUtc = $slotStart->copy()->utc();
                $slotEndUtc = $slotEnd->copy()->utc();

                $conflict = $this->hasConflict($appointments, $slotStartUtc, $slotEndUtc);

                if (!$conflict) {
                    $slots[] = [
                        'start_time' => $slotStartUtc->format('H:i'),
                        'end_time' => $slotEndUtc->format('H:i'),
                    ];
                }

                $start->addMinutes($this->slotDuration);
            }
        }

        return $slots;
    }

    /**
     * Parse and validate a date string
     */
    private function parseDate(string $date): Carbon
    {
        try {
            return Carbon::parse($date);
        } catch (\Exception $e) {
            throw new InvalidArgumentException('Invalid date format provided');
        }
    }

    /**
     * Get doctor's availabilities for a specific day of the week
     */
    private function getDoctorAvailabilities(int $doctorProfileId, string $dayOfWeek)
    {
        return Availability::where('doctor_id', $doctorProfileId)
            ->where('weekday', $dayOfWeek)
            ->get();
    }

    /**
     * Get doctor's appointments for a specific date
     */
    private function getDoctorAppointments(int $doctorUserId, string $date)
    {
        return Appointment::where('doctor_id', $doctorUserId)
            ->whereNotIn('status', [AppointmentStatus::PENDING->value, AppointmentStatus::CANCELLED->value])
            ->whereDate('date', $date)
            ->get()
            ->map(function ($appointment) {
                return [
                    'start' => Carbon::parse($appointment->start_time),
                    'end' => Carbon::parse($appointment->end_time),
                ];
            });
    }

    /**
     * Generate available slots based on doctor's availabilities
     */
    private function generateAvailableSlots($availabilities, string $date, string $doctorTimezone, string $patientTimezone, Carbon $minStartTime, $appointments, Carbon $parsedDate): array
    {
        $slots = [];

        foreach ($availabilities as $availability) {
            // Create time-only Carbon instances in doctor's timezone
            $startTime = Carbon::createFromFormat('H:i:s', $availability->start_time, new DateTimeZone($doctorTimezone));
            $endTime = Carbon::createFromFormat('H:i:s', $availability->end_time, new DateTimeZone($doctorTimezone));

            // Set the date without altering the time
            $start = $startTime->setDateFrom(Carbon::parse($date, new DateTimeZone($doctorTimezone)));
            $end = $endTime->setDateFrom(Carbon::parse($date, new DateTimeZone($doctorTimezone)));

            while ($start->copy()->addMinutes($this->slotDuration)->lte($end)) {
                $slotStart = $start->copy();
                $slotEnd = $start->copy()->addMinutes($this->slotDuration);

                // Skip slots that start less than 3 hours from now
                if ($slotStart->lt($minStartTime) && $parsedDate->isToday()) {
                    $start->addMinutes($this->slotDuration);
                    continue;
                }

                $conflict = $this->checkTimeConflict($appointments, $slotStart, $slotEnd, $date, $doctorTimezone);

                if (!$conflict) {
                    // Convert to patient's timezone for display
                    $patientStartTime = $slotStart->copy()->setTimezone($patientTimezone);
                    $patientEndTime = $slotEnd->copy()->setTimezone($patientTimezone);

                    $slots[] = [
                        'start_time' => $patientStartTime->format('H:i'),
                        'end_time' => $patientEndTime->format('H:i'),
                    ];
                }

                $start->addMinutes($this->slotDuration);
            }
        }

        return $slots;
    }

    /**
     * Check if there's a time conflict with existing appointments
     */
    private function checkTimeConflict($appointments, Carbon $slotStart, Carbon $slotEnd, string $date, string $doctorTimezone): bool
    {
        return $appointments->contains(function ($range) use ($slotStart, $slotEnd, $date, $doctorTimezone) {
            $rangeStart = Carbon::createFromFormat('H:i:s', $range['start']->format('H:i:s'), new DateTimeZone($doctorTimezone));
            $rangeEnd = Carbon::createFromFormat('H:i:s', $range['end']->format('H:i:s'), new DateTimeZone($doctorTimezone));
            $rangeStart = $rangeStart->setDateFrom(Carbon::parse($date, new DateTimeZone($doctorTimezone)));
            $rangeEnd = $rangeEnd->setDateFrom(Carbon::parse($date, new DateTimeZone($doctorTimezone)));
            return $slotStart->lt($rangeEnd) && $slotEnd->gt($rangeStart);
        });
    }

    /**
     * Check if there's a conflict with existing appointments in UTC
     */
    private function hasConflict($appointments, Carbon $slotStartUtc, Carbon $slotEndUtc): bool
    {
        return $appointments->contains(function ($range) use ($slotStartUtc, $slotEndUtc) {
            return $slotStartUtc->lt($range['end']) && $slotEndUtc->gt($range['start']);
        });
    }
}
