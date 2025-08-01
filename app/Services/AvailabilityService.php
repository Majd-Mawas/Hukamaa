<?php

namespace App\Services;

use Carbon\Carbon;
use Modules\DoctorManagement\App\Models\Availability;
use Modules\AppointmentManagement\App\Models\Appointment;
use Modules\DoctorManagement\App\Models\DoctorProfile;
use InvalidArgumentException;

class AvailabilityService
{
    private int $defaultSlotDuration = 60; // in minutes

    public function __construct(private ?int $slotDuration = null)
    {
        $this->slotDuration = $slotDuration ?? $this->defaultSlotDuration;
    }

    public function getAvailableSlots(int $doctorProfileId, string $date, ?string $timezone = null): array
    {
        // Validate date format
        try {
            $date = Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            throw new InvalidArgumentException('Invalid date format provided');
        }

        $timezone = $timezone ?? config('app.timezone');
        $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));

        // 1. Fetch doctor's weekly availability for that weekday
        $availabilities = Availability::where('doctor_id', $doctorProfileId)
            ->where('weekday', $dayOfWeek)
            ->get();

        if ($availabilities->isEmpty()) {
            return [];
        }

        // 2. Fetch doctor's user ID
        $doctorProfile = DoctorProfile::find($doctorProfileId);
        if (!$doctorProfile) {
            return [];
        }

        // 3. Fetch appointments on that date with timezone consideration
        $appointments = Appointment::where('doctor_id', $doctorProfile->user_id)
            ->whereDate('date', $date)
            ->get()
            ->map(function ($appointment) {
                return [
                    'start' => Carbon::parse($appointment->start_time),
                    'end' => Carbon::parse($appointment->end_time),
                ];
            });

        // 4. Generate slots and remove conflicts
        $slots = [];

        foreach ($availabilities as $availability) {
            $start = Carbon::parse($availability->start_time);
            $end = Carbon::parse($availability->end_time);

            while ($start->copy()->addMinutes($this->slotDuration)->lte($end)) {
                $slotStart = $start->copy();
                $slotEnd = $start->copy()->addMinutes($this->slotDuration);

                $conflict = $appointments->contains(function ($range) use ($slotStart, $slotEnd) {
                    return $slotStart->lt($range['end']) && $slotEnd->gt($range['start']);
                });

                if (!$conflict) {
                    $slots[] = [
                        'start_time' => $slotStart->format('H:i'),
                        'end_time' => $slotEnd->format('H:i'),
                        'timezone' => $timezone,
                    ];
                }

                $start->addMinutes($this->slotDuration);
            }
        }

        return $slots;
    }

    public function setSlotDuration(int $minutes): self
    {
        if ($minutes <= 0) {
            throw new InvalidArgumentException('Slot duration must be greater than 0');
        }
        $this->slotDuration = $minutes;
        return $this;
    }
}
