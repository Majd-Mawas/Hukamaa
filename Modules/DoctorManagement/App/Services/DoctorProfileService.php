<?php

namespace Modules\DoctorManagement\App\Services;

use Modules\DoctorManagement\App\Models\DoctorProfile;

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

    /**
     * Get featured doctors for the home page with search capabilities
     *
     * @param int $limit Number of doctors to return
     * @param string|null $query Search query for doctor name or email
     * @param string|null $gender Filter by gender
     * @param int|null $specializationId Filter by specialization
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFeaturedDoctors(
        int $limit = 10,
        ?string $query = null,
        ?string $gender = null,
        ?int $specializationId = null
    ) {
        return DoctorProfile::with(['user', 'specialization', 'availabilities'])
            ->where('status', 'approved')
            ->when($query, function ($q) use ($query) {
                $q->whereHas('user', function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%");
                });
            })
            ->when($gender, function ($q) use ($gender) {
                $q->whereHas('user', function ($q) use ($gender) {
                    $q->where('gender', $gender);
                });
            })
            ->when($specializationId, function ($q) use ($specializationId) {
                $q->where('specialization_id', $specializationId);
            })
            ->whereHas('availabilities', function ($query) {
                $query->where(function ($q) {
                    $days = collect(range(0, 6))->map(function ($day) {
                        return now()->addDays($day)->format('l');
                    })->toArray();
                    $q->whereIn('weekday', $days);
                });
            })
            ->orderBy('experience_years', 'desc')
            ->latest()
            ->paginate($limit);
    }
}
