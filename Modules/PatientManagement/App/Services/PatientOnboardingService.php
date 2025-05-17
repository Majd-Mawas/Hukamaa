<?php

namespace Modules\PatientManagement\App\Services;

use Modules\PatientManagement\App\Models\PatientProfile;

class PatientOnboardingService
{
    public function updateBasicInfo(int $userId, array $data): PatientProfile
    {
        $profile = PatientProfile::updateOrCreate(
            ['user_id' => $userId],
            array_merge($data, ['is_profile_complete' => false])
        );

        return $profile->fresh();
    }

    public function updateExtraInfo(int $userId, array $data): PatientProfile
    {
        $profile = PatientProfile::where('user_id', $userId)->firstOrFail();

        $profile->update([
            'allergies' => $data['allergies'] ?? null,
            'current_medications' => $data['current_medications'] ?? null,
            'is_profile_complete' => true
        ]);

        if (isset($data['files'])) {
            foreach ($data['files'] as $file) {
                $profile->addMedia($file)
                    ->toMediaCollection('patient_files');
            }
        }

        return $profile->load('media')->fresh();
    }
}
