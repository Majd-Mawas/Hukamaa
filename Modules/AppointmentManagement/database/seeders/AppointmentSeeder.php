<?php

namespace Modules\AppointmentManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Modules\AppointmentManagement\App\Enums\AppointmentStatus;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        try {
            // Get all doctor and patient profiles
            $doctorProfiles = DB::table('doctor_profiles')->get();
            $patientProfiles = DB::table('patient_profiles')->get();

            if ($doctorProfiles->isEmpty() || $patientProfiles->isEmpty()) {
                throw new \Exception('Doctor or patient profiles not found. Please run DoctorProfileSeeder and PatientProfileSeeder first.');
            }

            // Get all availabilities
            $availabilities = DB::table('availabilities')->get();
            if ($availabilities->isEmpty()) {
                throw new \Exception('No availabilities found. Please run AvailabilitySeeder first.');
            }

            // Create appointments for the next 30 days
            for ($i = 0; $i < 50; $i++) {
                $doctor = $doctorProfiles->random();
                $patient = $patientProfiles->random();
                $appointmentDate = $faker->dateTimeBetween('now', '+30 days');
                $dayOfWeek = strtolower($appointmentDate->format('l'));

                // Get doctor's availability for this day
                $availability = $availabilities
                    ->where('doctor_id', $doctor->id)
                    ->where('weekday', $dayOfWeek)
                    ->first();

                if ($availability) {
                    DB::table('appointments')->insert([
                        'doctor_id' => $doctor->id,
                        'patient_id' => $patient->id,
                        'date' => $appointmentDate,
                        'start_time' => $availability->start_time,
                        'end_time' => $availability->end_time,
                        'status' => $faker->randomElement(array_column(AppointmentStatus::cases(), 'value')),
                        'description' => $faker->sentence(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Verify appointments were created
            $appointmentCount = DB::table('appointments')->count();
            if ($appointmentCount === 0) {
                throw new \Exception('No appointments were created. Please check doctor availabilities.');
            }

            $this->command->info("Created {$appointmentCount} appointments successfully.");
        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }
    }
}
