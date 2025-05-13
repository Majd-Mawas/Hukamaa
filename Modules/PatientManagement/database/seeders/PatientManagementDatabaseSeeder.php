<?php

namespace Modules\PatientManagement\Database\seeders;

use Illuminate\Database\Seeder;
use Modules\PatientManagement\Database\seeders\PatientProfileSeeder;
use Modules\PatientManagement\Database\seeders\PreConsultationFormSeeder;

class PatientManagementDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            PatientProfileSeeder::class,
            PreConsultationFormSeeder::class,
        ]);
    }
}
