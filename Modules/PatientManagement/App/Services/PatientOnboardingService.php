<?php

namespace Modules\PatientManagement\App\Services;

use Modules\PatientManagement\App\Models\PatientProfile;

class PatientOnboardingService
{
    public function updateBasicInfo(int $userId, array $data): PatientProfile
    {
        $profile = PatientProfile::updateOrCreate(
            [
                'user_id' => $userId
            ],
            [
                'is_profile_complete' => false,
                'medical_history' => $data['medical_history'],
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'phone_number' => $data['phone_number'],
            ]
        );

        $profile->chronicConditions()->sync($data['chronic_conditions']);

        return $profile->fresh();
    }

    public function updateExtraInfo(int $userId, array $data): PatientProfile
    {
        $profile = PatientProfile::where('user_id', $userId)->firstOrFail();

        $profile->update([
            'current_medications' => $data['current_medications'] ?? null,
            'is_profile_complete' => true
        ]);

        $profile->allergies()->sync($data['allergies']);

        if (isset($data['files'])) {
            foreach ($data['files'] as $file) {
                $profile->addMedia($file)
                    ->toMediaCollection('patient_files');
            }
        }

        return $profile->load('media')->fresh();
    }
}
