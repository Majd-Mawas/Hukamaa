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
            \Modules\SpecializationManagement\Database\Seeders\SpecializationManagementDatabaseSeeder::class,

            // Then seed doctors and their availabilities
            \Modules\DoctorManagement\Database\Seeders\DoctorManagementDatabaseSeeder::class,

            // Then seed patients
            \Modules\PatientManagement\Database\Seeders\PatientManagementDatabaseSeeder::class,

            // Then seed appointments
            \Modules\AppointmentManagement\Database\Seeders\AppointmentManagementDatabaseSeeder::class,

            // Finally, seed payments and pre-consultation forms
            \Modules\PaymentManagement\Database\Seeders\PaymentManagementDatabaseSeeder::class,
        ]);
    }
}
