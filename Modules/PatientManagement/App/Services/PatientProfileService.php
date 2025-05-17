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
                'allergies' => $data['allergies'] ?? null,
            ]
        );

        if (isset($data['files'])) {
            foreach ($data['files'] as $file) {
                $profile->addMedia($file)
                    ->toMediaCollection('patient_files');
            }
        }

        return $profile->fresh();
    }
}
