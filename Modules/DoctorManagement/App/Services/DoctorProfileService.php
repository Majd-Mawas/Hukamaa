<?php

namespace Modules\DoctorManagement\App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\DoctorManagement\App\Http\Requests\web\UpdateDoctorProfileRequest;
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

    public function getFeaturedDoctors(
        int $limit = 10,
        ?string $query = null,
        ?string $gender = null,
        ?int $specializationId = null
    ) {
        return DoctorProfile::with(['user', 'specialization', 'availabilities', 'media'])
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

    public function updateProfile(UpdateDoctorProfileRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $user = Auth::User();
            $doctor = $user->doctorProfile;

            // Update user details
            $user->update([
                'name' => $request->name,
                // 'email' => $request->email,
            ]);

            if ($request->hasFile('profile_picture')) {
                // Clear existing media collection before adding new one
                $doctor->clearMediaCollection('profile_picture');

                // Add new profile_picture to the media collection
                $doctor->addMediaFromRequest('profile_picture')
                    ->toMediaCollection('profile_picture');
            }

            // Update doctor details
            $doctor->update([
                'specialization_id' => $request->specialization,
                'experience_description' => $request->experience,
                // 'experience_years' => $request->experience_years,
                // 'consultation_fee' => $request->consultation_fee,
                'phone_number' => $request->phone
            ]);

            return $doctor->fresh(['user', 'specialization']);
        });
    }
}
