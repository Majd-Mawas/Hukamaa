<?php

namespace Modules\DoctorManagement\Database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Modules\DoctorManagement\App\Enums\DoctorStatus;
use Modules\DoctorManagement\App\Enums\DoctorTitle;
use Modules\DoctorManagement\App\Enums\Gender;

class DoctorProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        try {
            // Get all doctor users
            $doctorUsers = DB::table('users')
                ->where('role', 'doctor')
                ->get();

            if ($doctorUsers->isEmpty()) {
                throw new \Exception('No doctor users found. Please run UserSeeder first.');
            }

            // Get all specializations
            $specializations = DB::table('specializations')->get();

            if ($specializations->isEmpty()) {
                throw new \Exception('No specializations found. Please run SpecializationSeeder first.');
            }

            foreach ($doctorUsers as $user) {
                $services = $faker->randomElements(['remote_video_consultation', 'home_visit'], $faker->numberBetween(1, 2));
                $hasHomeVisit = in_array('home_visit', $services);

                DB::table('doctor_profiles')->insert([
                    'user_id' => $user->id,
                    'gender' => $faker->randomElement(array_column(Gender::cases(), 'value')),
                    'birth_date' => $faker->dateTimeBetween('-50 years', '-30 years'),
                    'phone_number' => $faker->phoneNumber,
                    'address' => $faker->address,
                    'profile_picture' => null, // Will be handled by media library
                    'specialization_id' => $specializations->random()->id,
                    'title' => $faker->randomElement(array_column(DoctorTitle::cases(), 'value')),
                    'experience_years' => $faker->numberBetween(1, 30),
                    'experience_description' => $faker->paragraph,
                    'status' => DoctorStatus::APPROVED->value,
                    'services' => json_encode($services),
                    'coverage_area' => $hasHomeVisit ? $faker->city : null,
                    'expertise_focus' => $faker->optional(0.7)->sentence,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }
    }
}
