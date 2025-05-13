<?php

namespace Modules\DoctorManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specializations = [
            [
                'department_name' => 'Cardiology',
                'specialization_name' => 'Heart and cardiovascular system specialist',
            ],
            [
                'department_name' => 'Dermatology',
                'specialization_name' => 'Skin, hair, and nail specialist',
            ],
            [
                'department_name' => 'Neurology',
                'specialization_name' => 'Brain and nervous system specialist',
            ],
            [
                'department_name' => 'Pediatrics',
                'specialization_name' => 'Children\'s health specialist',
            ],
            [
                'department_name' => 'Orthopedics',
                'specialization_name' => 'Bone and joint specialist',
            ],
            [
                'department_name' => 'Gynecology',
                'specialization_name' => 'Women\'s health specialist',
            ],
            [
                'department_name' => 'Ophthalmology',
                'specialization_name' => 'Eye specialist',
            ],
            [
                'department_name' => 'ENT',
                'specialization_name' => 'Ear, nose, and throat specialist',
            ],
            [
                'department_name' => 'Dentistry',
                'specialization_name' => 'Oral health specialist',
            ],
            [
                'department_name' => 'Psychiatry',
                'specialization_name' => 'Mental health specialist',
            ],
            [
                'department_name' => 'General Medicine',
                'specialization_name' => 'Primary care physician',
            ],
            [
                'department_name' => 'Endocrinology',
                'specialization_name' => 'Hormone and metabolism specialist',
            ],
        ];

        foreach ($specializations as $specialization) {
            DB::table('specializations')->updateOrInsert(
                [
                    'department_name' => $specialization['department_name'],
                    'specialization_name' => $specialization['specialization_name']
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
