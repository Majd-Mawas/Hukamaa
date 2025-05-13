<?php

namespace Modules\DoctorManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Modules\DoctorManagement\App\Enums\UserRole;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        try {
            // Create admin users
            for ($i = 0; $i < 3; $i++) {
                DB::table('users')->insert([
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'password' => Hash::make('password'),
                    'role' => UserRole::ADMIN->value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Create regular users (patients)
            for ($i = 0; $i < 50; $i++) {
                DB::table('users')->insert([
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'password' => Hash::make('password'),
                    'role' => UserRole::PATIENT->value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Create doctor users
            for ($i = 0; $i < 20; $i++) {
                DB::table('users')->insert([
                    'name' => 'Dr. ' . $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'password' => Hash::make('password'),
                    'role' => UserRole::DOCTOR->value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $this->command->info('Users seeded successfully:');
            $this->command->info('- 3 Admin users');
            $this->command->info('- 50 Patient users');
            $this->command->info('- 20 Doctor users');
        } catch (\Exception $e) {
            $this->command->error('Error seeding users: ' . $e->getMessage());
        }
    }
}
