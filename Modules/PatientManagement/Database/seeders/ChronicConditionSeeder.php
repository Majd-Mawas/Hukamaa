<?php

namespace Modules\PatientManagement\Database\seeders;

use Illuminate\Database\Seeder;
use Modules\PatientManagement\App\Models\ChronicCondition;

class ChronicConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ChronicCondition::factory(15)->create();
    }
}
