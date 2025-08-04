<?php

namespace Modules\DoctorManagement\App\Services;

use Carbon\Carbon;
use Modules\DoctorManagement\App\Models\Availability;
use Modules\AppointmentManagement\App\Models\Appointment;
use Modules\DoctorManagement\App\Models\DoctorProfile;
use App\Services\TimezoneService;
use InvalidArgumentException;
use Modules\AppointmentManagement\App\Enums\AppointmentStatus;

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

    public function getAvailableSlots(int $doctorProfileId, string $date, ?string $patientTimezone = null): array
    {
        $patientTimezone = $patientTimezone ?? $this->timezoneService->getUserTimezone();

        try {
            $parsedDate = Carbon::parse($date);
            $date = $parsedDate->format('Y-m-d');
        } catch (\Exception $e) {
            throw new InvalidArgumentException('Invalid date format provided');
        }

        $dayOfWeek = strtolower($parsedDate->format('l'));

        $availabilities = Availability::where('doctor_id', $doctorProfileId)
            ->where('weekday', $dayOfWeek)
            ->get();

        if ($availabilities->isEmpty()) {
            return [];
        }

        $doctorProfile = DoctorProfile::find($doctorProfileId);
        if (!$doctorProfile) {
            return [];
        }

        // Get doctor's timezone
        $doctorTimezone = $doctorProfile->user->timezone ?? 'UTC';

        // Get current time in doctor's timezone
        $now = Carbon::now()->setTimezone($doctorTimezone);
        // Calculate minimum start time (3 hours from now)
        $minStartTime = $now->copy()->addHours(3);

        $appointments = Appointment::where('doctor_id', $doctorProfile->user_id)
            ->whereNotIn('status', [AppointmentStatus::PENDING->value, AppointmentStatus::CANCELLED->value])
            ->whereDate('date', $date)
            ->get()
            ->map(function ($appointment) {
                return [
                    'start' => Carbon::parse($appointment->start_time),
                    'end' => Carbon::parse($appointment->end_time),
                ];
            });
        $slots = [];

        foreach ($availabilities as $availability) {
            // Parse availability times in doctor's timezone
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $availability->start_time, $doctorTimezone);
            $end = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $availability->end_time, $doctorTimezone);

            while ($start->copy()->addMinutes($this->slotDuration)->lte($end)) {
                $slotStart = $start->copy();
                $slotEnd = $start->copy()->addMinutes($this->slotDuration);

                // Skip slots that start less than 3 hours from now
                if ($slotStart->lt($minStartTime) && $parsedDate->isToday()) {
                    $start->addMinutes($this->slotDuration);
                    continue;
                }

                $conflict = $appointments->contains(function ($range) use ($slotStart, $slotEnd, $date) {
                    $rangeStart = Carbon::parse($range['start'])->setDateFrom(Carbon::parse($date));
                    $rangeEnd = Carbon::parse($range['end'])->setDateFrom(Carbon::parse($date));
                    return $slotStart->lt($rangeEnd) && $slotEnd->gt($rangeStart);
                });

                if (!$conflict) {
                    // Convert to patient's timezone for display
                    $patientStartTime = $slotStart->copy()->setTimezone($patientTimezone);
                    $patientEndTime = $slotEnd->copy()->setTimezone($patientTimezone);

                    $slots[] = [
                        'start_time' => $patientStartTime->format('H:i'),
                        'end_time' => $patientEndTime->format('H:i'),
                        // 'start_time_utc' => $slotStartUtc->format('H:i'), // Keep UTC for backend processing
                        // 'end_time_utc' => $slotEndUtc->format('H:i'),
                        // 'timezone' => $patientTimezone,
                        // 'formatted_time' => $patientStartTime->format('h:i A') . ' - ' . $patientEndTime->format('h:i A')
                    ];
                }

                $start->addMinutes($this->slotDuration);
            }
        }

        return $slots;
    }

    public function isSlotAvailable(int $doctorProfileId, string $date, string $startTime, string $endTime, ?string $patientTimezone = null): bool
    {
        $patientTimezone = $patientTimezone ?? $this->timezoneService->getUserTimezone();

        $availableSlots = $this->getAvailableSlots($doctorProfileId, $date);

        return in_array([
            'start_time' => Carbon::parse($startTime)->format('H:i'),
            'end_time' => Carbon::parse($endTime)->format('H:i')
        ], $availableSlots);
    }

    private function getAvailableSlotsInUtc(int $doctorProfileId, string $date): array
    {
        try {
            $parsedDate = Carbon::parse($date);
            $date = $parsedDate->format('Y-m-d');
        } catch (\Exception $e) {
            throw new InvalidArgumentException('Invalid date format provided');
        }

        $dayOfWeek = strtolower($parsedDate->format('l'));

        $availabilities = Availability::where('doctor_id', $doctorProfileId)
            ->where('weekday', $dayOfWeek)
            ->get();

        if ($availabilities->isEmpty()) {
            return [];
        }

        $doctorProfile = DoctorProfile::find($doctorProfileId);
        if (!$doctorProfile) {
            return [];
        }

        // Get doctor's timezone
        $doctorTimezone = $doctorProfile->user->timezone ?? 'UTC';

        $appointments = Appointment::where('doctor_id', $doctorProfile->user_id)
            ->whereDate('date', $date)
            ->get()
            ->map(function ($appointment) {
                return [
                    'start' => Carbon::parse($appointment->start_time),
                    'end' => Carbon::parse($appointment->end_time),
                ];
            });

        $slots = [];

        foreach ($availabilities as $availability) {
            // Parse availability times in doctor's timezone
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $availability->start_time, $doctorTimezone);
            $end = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $availability->end_time, $doctorTimezone);

            while ($start->copy()->addMinutes($this->slotDuration)->lte($end)) {
                $slotStart = $start->copy();
                $slotEnd = $start->copy()->addMinutes($this->slotDuration);

                // Convert to UTC for conflict checking
                $slotStartUtc = $slotStart->copy()->utc();
                $slotEndUtc = $slotEnd->copy()->utc();

                $conflict = $appointments->contains(function ($range) use ($slotStartUtc, $slotEndUtc) {
                    return $slotStartUtc->lt($range['end']) && $slotEndUtc->gt($range['start']);
                });

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
}
