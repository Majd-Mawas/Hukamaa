<?php

namespace Modules\DoctorManagement\App\Services;

use Modules\DoctorManagement\App\Models\DoctorProfile;
use Modules\DoctorManagement\App\Enums\DoctorStatus;

class DoctorOnboardingService
{
    public function updateBasicInfo(int $userId, array $data): DoctorProfile
    {
        $profile = DoctorProfile::updateOrCreate(
            ['user_id' => $userId],
            [
                'gender' => $data['gender'],
                'birth_date' => $data['birth_date'],
                'phone_number' => $data['phone_number'],
                'address' => $data['address'],
                'status' => DoctorStatus::PENDING->value
            ]
        );

        if (isset($data['profile_picture'])) {
            $profile->addMedia($data['profile_picture'])
                ->toMediaCollection('profile_picture');
        }

        if (isset($data['identity_document'])) {
            $profile->addMedia($data['identity_document'])
                ->toMediaCollection('identity_document');
        }

        return $profile->fresh();
    }

    public function updateMedicalInfo(int $userId, array $data): DoctorProfile
    {
        $profile = DoctorProfile::where('user_id', $userId)->firstOrFail();

        $updateData = [
            'specialization_id' => $data['specialization_id'],
            'title' => $data['title'],
            'experience_years' => $data['experience_years'],
            'experience_description' => $data['experience_description'],
            'services' => $data['services'],
            // 'expertise_focus' => $data['expertise_focus'] ?? null,
        ];

        if (in_array('home_visit', $data['services'])) {
            $profile->coverageAreas()->sync($data['coverage_area']);
        }

        $profile->update($updateData);

        return $profile->fresh();
    }

    public function uploadDocuments(int $userId, array $data): DoctorProfile
    {
        $profile = DoctorProfile::where('user_id', $userId)->firstOrFail();

        $profile->update(
            [
                'expertise_focus' => $data['expertise_focus'] ?? null,
            ]
        );

        // if (isset($data['practice_licenses'])) {
        //     $profile->addMedia($data['practice_licenses'])
        //         ->toMediaCollection('practice_licenses');
        // }

        if (isset($data['practice_licenses'])) {
            foreach ($data['practice_licenses'] as $certificate) {
                $profile->addMedia($certificate)
                    ->toMediaCollection('practice_licenses');
            }
        }

        if (isset($data['medical_certificates'])) {
            foreach ($data['medical_certificates'] as $certificate) {
                $profile->addMedia($certificate)
                    ->toMediaCollection('medical_certificates');
            }
        }

        return $profile->load('media')->fresh();
    }
}
