<?php

namespace Modules\PatientManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Modules\DoctorManagement\App\Enums\Gender;

class PatientProfileSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        try {
            // Get all patient users
            $patientUsers = DB::table('users')
                ->where('role', 'patient')
                ->get();

            if ($patientUsers->isEmpty()) {
                throw new \Exception('No patient users found. Please run UserSeeder first.');
            }

            foreach ($patientUsers as $user) {
                DB::table('patient_profiles')->insert([
                    'user_id' => $user->id,
                    'gender' => $faker->randomElement(array_column(Gender::cases(), 'value')),
                    'birth_date' => $faker->dateTimeBetween('-80 years', '-18 years'),
                    'medical_history' => json_encode($faker->optional(0.7)->words(5)),
                    'chronic_conditions' => json_encode($faker->optional(0.3)->words(3)),
                    'allergies' => json_encode($faker->optional(0.3)->words(2)),
                    'current_medications' => json_encode($faker->optional(0.4)->words(3)),
                    'is_profile_complete' => $faker->boolean(80),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }
    }
}
