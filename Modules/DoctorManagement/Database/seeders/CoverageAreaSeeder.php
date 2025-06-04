<?php

namespace Modules\DoctorManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoverageAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            'Aleppo',
            'Damascus',
            'Homs',
            'Latakia',
            'Tartus',
            'Hama',
            'Daraa',
            'Deir ez-Zor',
            'Raqqa',
            'Hasakah',
            'Quneitra',
            'Idlib',
            'As-Suwayda'
        ];

        foreach ($areas as $area) {
            DB::table('coverage_areas')->updateOrInsert(['name' => $area]);
        }
    }
}
