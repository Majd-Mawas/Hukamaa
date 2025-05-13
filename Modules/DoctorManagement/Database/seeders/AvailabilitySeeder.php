<?php

namespace Modules\DoctorManagement\Database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Modules\DoctorManagement\App\Enums\Weekday;

class AvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        try {
            // Get all doctor profiles
            $doctorProfiles = DB::table('doctor_profiles')->get();

            if ($doctorProfiles->isEmpty()) {
                throw new \Exception('No doctor profiles found. Please run DoctorProfileSeeder first.');
            }

            $weekdays = array_column(Weekday::cases(), 'value');
            $timeRanges = [
                ['09:00:00', '12:00:00'],
                ['14:00:00', '17:00:00'],
                ['18:00:00', '21:00:00']
            ];

            foreach ($doctorProfiles as $profile) {
                // Each doctor works 4-6 days per week
                $workingDays = $faker->randomElements($weekdays, $faker->numberBetween(4, 6));

                foreach ($workingDays as $weekday) {
                    $timeRange = $faker->randomElement($timeRanges);
                    DB::table('availabilities')->insert([
                        'doctor_id' => $profile->id,
                        'weekday' => $weekday,
                        'start_time' => $timeRange[0],
                        'end_time' => $timeRange[1],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }
    }
}
