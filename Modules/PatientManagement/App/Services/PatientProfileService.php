<?php

namespace Modules\PatientManagement\App\Services;

use Modules\PatientManagement\App\Models\PatientProfile;
use Modules\UserManagement\App\Models\User;

class PatientProfileService
{
    public function updateProfile(int $userId, array $data): PatientProfile
    {
        $user = User::findOrFail($userId);

        $user->update([
            'name' => $data['name'],
        ]);

        $profile = PatientProfile::updateOrCreate(
            ['user_id' => $userId],
            [
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'medical_history' => $data['medical_history'],
                'current_medications' => $data['current_medications'],
                'phone_number' => $data['phone_number'],
                // 'allergies' => $data['allergies'] ?? null,
            ]
        );

        $profile->allergies()->sync($data['allergies']);

        $profile->chronicConditions()->sync($data['chronic_conditions']);

        if (isset($data['files'])) {
            foreach ($data['files'] as $file) {
                $profile->addMedia($file)
                    ->toMediaCollection('patient_files');
            }
        }

        return $profile->fresh();
    }
}
