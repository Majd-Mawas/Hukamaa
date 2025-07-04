<?php

namespace Modules\DoctorManagement\App\Services;

use Carbon\Carbon;
use Modules\DoctorManagement\App\Models\Availability;
use Modules\AppointmentManagement\App\Models\Appointment;
use Modules\DoctorManagement\App\Models\DoctorProfile;
use InvalidArgumentException;

class DoctorAvailabilityService
{
    private int $defaultSlotDuration = 60; // in minutes

    public function __construct(private ?int $slotDuration = null)
    {
        $this->slotDuration = $slotDuration ?? $this->defaultSlotDuration;
    }

    public function getAvailableSlots(int $doctorProfileId, string $date): array
    {
        // Validate date format
        try {
            $parsedDate = Carbon::parse($date);
            $date = $parsedDate->format('Y-m-d');
        } catch (\Exception $e) {
            throw new InvalidArgumentException('Invalid date format provided');
        }

        // Check if date is more than two weeks from now
        if (Carbon::now()->diffInDays($parsedDate) > 14) {
            return [];
        }

        $dayOfWeek = strtolower($parsedDate->format('l'));

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
                    ];
                }

                $start->addMinutes($this->slotDuration);
            }
        }

        return $slots;
    }

    public function isSlotAvailable(int $doctorProfileId, string $date, string $startTime, string $endTime): bool
    {
        $availableSlots = $this->getAvailableSlots($doctorProfileId, $date);

        return in_array([
            'start_time' => Carbon::parse($startTime)->format('H:i'),
            'end_time' => Carbon::parse($endTime)->format('H:i')
        ], $availableSlots);
    }
}
