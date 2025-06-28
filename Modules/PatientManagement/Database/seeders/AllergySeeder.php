<?php

namespace Modules\PatientManagement\Database\seeders;

use Illuminate\Database\Seeder;
use Modules\PatientManagement\App\Models\Allergy;

class AllergySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Allergy::factory(15)->create();
    }
}
