<?php

namespace Modules\DoctorManagement\App\Services;

use Auth;
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

    public function getAvailabilitiesByWeekday(int $doctorId)
    {
        $availabilities = $this->getDoctorAvailabilities($doctorId);

        $groupedAvailabilities = [];
        foreach ($availabilities as $availability) {
            $groupedAvailabilities[$availability->weekday][] = $availability;
        }

        return $groupedAvailabilities;
    }

    public function createAvailability(array $data): Availability
    {
        $data['doctor_id'] = Auth::user()->doctorProfile->id;
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

    public function getAvailability($id)
    {
        return Availability::findOrFail($id);
    }
}
