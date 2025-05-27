<?php

namespace Modules\AppointmentManagement\Database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Modules\AppointmentManagement\App\Enums\AppointmentStatus;

class AppointmentReportSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        try {
            // Get completed appointments
            $completedAppointments = DB::table('appointments')
                ->where('status', AppointmentStatus::COMPLETED->value)
                ->get();

            if ($completedAppointments->isEmpty()) {
                throw new \Exception('No completed appointments found. Please run AppointmentSeeder first.');
            }

            foreach ($completedAppointments as $appointment) {
                // Create report for each completed appointment
                DB::table('appointments_reports')->insert([
                    'appointment_id' => $appointment->id,
                    'diagnosis' => $faker->paragraph(2),
                    'prescription' => $faker->text(200),
                    'additional_notes' => $faker->optional(0.7)->text(150),
                    'created_at' => $appointment->updated_at,
                    'updated_at' => $appointment->updated_at,
                ]);
            }
        } catch (\Exception $e) {
            echo "Error seeding appointment reports: {$e->getMessage()}\n";
        }
    }
}
