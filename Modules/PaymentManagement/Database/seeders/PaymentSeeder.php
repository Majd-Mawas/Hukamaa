<?php

namespace Modules\PaymentManagement\Database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Modules\PaymentManagement\App\Enums\PaymentStatus;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        try {
            // Get all patients and doctors
            $patients = DB::table('users')
                ->where('role', 'patient')
                ->get();
            $doctors = DB::table('users')
                ->where('role', 'doctor')
                ->get();
            $admins = DB::table('users')
                ->where('role', 'admin')
                ->get();

            if ($patients->isEmpty() || $doctors->isEmpty()) {
                throw new \Exception('No patients or doctors found. Please run UserSeeder first.');
            }

            if ($admins->isEmpty()) {
                throw new \Exception('No admin users found. Please run UserSeeder first.');
            }

            $paymentCount = 0;
            // Create 2-3 payments per patient
            foreach ($patients as $patient) {
                $numPayments = $faker->numberBetween(2, 3);
                for ($i = 0; $i < $numPayments; $i++) {
                    $doctor = $doctors->random();
                    $paymentStatus = $faker->randomElement(['pending', 'approved', 'rejected']);

                    DB::table('payments')->insert([
                        'patient_id' => $patient->id,
                        'doctor_id' => $doctor->id,
                        'amount' => $faker->randomFloat(2, 50, 500),
                        'status' => $paymentStatus,
                        'approved_by' => $paymentStatus === 'approved' ? $admins->random()->id : null,
                        'approved_at' => $paymentStatus === 'approved' ? now() : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $paymentCount++;
                }
            }

            $this->command->info("Created {$paymentCount} payments successfully.");
        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }
    }
}
