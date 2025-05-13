<?php

namespace Modules\PatientManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Modules\PatientManagement\App\Enums\FormStatus;

class PreConsultationFormSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        try {
            // Get all patients and doctors
            $patients = DB::table('users')
                ->where('role', 'patient')
                ->get();
            $doctors = DB::table('users')
                ->where('role', 'doctor')
                ->get();

            if ($patients->isEmpty() || $doctors->isEmpty()) {
                throw new \Exception('No patients or doctors found. Please run UserSeeder first.');
            }

            $formCount = 0;
            // Create 1-2 forms per patient
            foreach ($patients as $patient) {
                $numForms = $faker->numberBetween(1, 2);
                for ($i = 0; $i < $numForms; $i++) {
                    $doctor = $doctors->random();
                    $formStatus = $faker->randomElement([
                        FormStatus::PENDING->value,
                        FormStatus::ACCEPTED->value,
                        FormStatus::REJECTED->value
                    ]);

                    DB::table('pre_consultation_forms')->insert([
                        'patient_id' => $patient->id,
                        'doctor_id' => $doctor->id,
                        'symptoms' => json_encode($faker->randomElements([
                            'Fever',
                            'Cough',
                            'Headache',
                            'Fatigue',
                            'Muscle pain',
                            'Sore throat',
                            'Shortness of breath',
                            'Loss of taste',
                            'Loss of smell',
                            'Nausea',
                            'Diarrhea'
                        ], $faker->numberBetween(1, 5))),
                        'condition_description' => $faker->paragraph(),
                        'status' => $formStatus,
                        'response_at' => $formStatus !== FormStatus::PENDING->value ? now() : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $formCount++;
                }
            }

            $this->command->info("Created {$formCount} pre-consultation forms successfully.");
        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }
    }
}
