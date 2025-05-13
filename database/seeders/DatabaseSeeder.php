<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // First, seed specializations as they are required by doctors
            \Modules\SpecializationManagement\Database\seeders\SpecializationManagementDatabaseSeeder::class,

            // Then seed doctors and their availabilities
            \Modules\DoctorManagement\Database\seeders\DoctorManagementDatabaseSeeder::class,

            // Then seed patients
            \Modules\PatientManagement\Database\seeders\PatientManagementDatabaseSeeder::class,

            // Then seed appointments
            \Modules\AppointmentManagement\Database\seeders\AppointmentManagementDatabaseSeeder::class,

            // Finally, seed payments and pre-consultation forms
            \Modules\PaymentManagement\Database\seeders\PaymentManagementDatabaseSeeder::class,
        ]);
    }
}
