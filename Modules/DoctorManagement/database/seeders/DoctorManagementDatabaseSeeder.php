<?php

namespace Modules\DoctorManagement\Database\Seeders;

use Illuminate\Database\Seeder;

class DoctorManagementDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            DoctorProfileSeeder::class,
            AvailabilitySeeder::class,
        ]);
    }
}
