<?php

namespace Modules\SpecializationManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SpecializationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $specializations = [
            [
                'department_name' => 'Internal Medicine',
                'specialization_name' => 'Cardiology',
                'description' => 'Specializes in diagnosing and treating heart disorders, including heart disease, heart failure, and heart rhythm problems.'
            ],
            [
                'department_name' => 'Internal Medicine',
                'specialization_name' => 'Gastroenterology',
                'description' => 'Focuses on the digestive system and its disorders, including diseases of the esophagus, stomach, small intestine, colon, liver, pancreas, and gallbladder.'
            ],
            [
                'department_name' => 'Surgery',
                'specialization_name' => 'Orthopedics',
                'description' => 'Deals with the correction of deformities of bones or muscles, including joint replacements, sports injuries, and spinal disorders.'
            ],
            [
                'department_name' => 'Surgery',
                'specialization_name' => 'Neurosurgery',
                'description' => 'Specializes in the surgical treatment of disorders affecting the nervous system, including the brain, spinal cord, and peripheral nerves.'
            ],
            [
                'department_name' => 'Pediatrics',
                'specialization_name' => 'Pediatric Cardiology',
                'description' => 'Focuses on heart problems in children, from birth defects to acquired heart diseases.'
            ],
            [
                'department_name' => 'Pediatrics',
                'specialization_name' => 'Pediatric Neurology',
                'description' => 'Specializes in the diagnosis and treatment of neurological disorders in children, including epilepsy, developmental disorders, and brain injuries.'
            ],
            [
                'department_name' => 'Obstetrics and Gynecology',
                'specialization_name' => 'Gynecologic Oncology',
                'description' => 'Focuses on the diagnosis and treatment of cancers of the female reproductive system.'
            ],
            [
                'department_name' => 'Obstetrics and Gynecology',
                'specialization_name' => 'Reproductive Endocrinology',
                'description' => 'Specializes in hormonal functioning as it relates to reproduction and infertility.'
            ],
            [
                'department_name' => 'Dermatology',
                'specialization_name' => 'Dermatopathology',
                'description' => 'Combines dermatology and pathology to diagnose skin diseases at a microscopic level.'
            ],
            [
                'department_name' => 'Dermatology',
                'specialization_name' => 'Pediatric Dermatology',
                'description' => 'Focuses on skin conditions affecting children, from birth to adolescence.'
            ]
        ];

        foreach ($specializations as $specialization) {
            DB::table('specializations')->insert([
                'department_name' => $specialization['department_name'],
                'specialization_name' => $specialization['specialization_name'],
                'description' => $specialization['description'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
