<?php

namespace Modules\DoctorManagement\Services;

use Modules\DoctorManagement\Models\DoctorProfile;

class DoctorProfileService
{
    public function getAllDoctors()
    {
        return DoctorProfile::with(['user', 'specialization'])
            ->where('status', 'approved')
            ->latest()
            ->paginate(10);
    }

    public function createDoctorProfile(array $data): DoctorProfile
    {
        return DoctorProfile::create($data);
    }

    public function updateDoctorProfile(DoctorProfile $doctorProfile, array $data): DoctorProfile
    {
        $doctorProfile->update($data);
        return $doctorProfile->fresh();
    }

    public function deleteDoctorProfile(DoctorProfile $doctorProfile): bool
    {
        return $doctorProfile->delete();
    }
}
