<?php

namespace Modules\AppointmentManagement\Database\seeders;

use Illuminate\Database\Seeder;
use Modules\AppointmentManagement\Database\seeders\AppointmentSeeder;

class AppointmentManagementDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            AppointmentSeeder::class,
            AppointmentReportSeeder::class,
        ]);
    }
}
