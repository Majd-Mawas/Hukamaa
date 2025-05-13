<?php

namespace Modules\DoctorManagement\App\Services;

use Modules\DoctorManagement\App\Models\Availability;

class AvailabilityService
{
    public function getDoctorAvailabilities(int $doctorId)
    {
        return Availability::where('doctor_id', $doctorId)
            ->orderBy('weekday')
            ->orderBy('start_time')
            ->get();
    }

    public function createAvailability(array $data): Availability
    {
        return Availability::create($data);
    }

    public function updateAvailability(Availability $availability, array $data): Availability
    {
        $availability->update($data);
        return $availability->fresh();
    }

    public function deleteAvailability(Availability $availability): bool
    {
        return $availability->delete();
    }
}
