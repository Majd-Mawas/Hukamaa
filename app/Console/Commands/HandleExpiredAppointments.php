<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Modules\AppointmentManagement\App\Models\Appointment;
use Modules\AppointmentManagement\App\Enums\AppointmentStatus;

class HandleExpiredAppointments extends Command
{
    protected $signature = 'appointments:handle-expired';
    protected $description = 'Update status of expired appointments that were not completed or cancelled';

    public function handle()
    {
        $now = Carbon::now();

        $expiredAppointments = Appointment::where('status', AppointmentStatus::SCHEDULED)
            ->whereDate('date', '<=', $now->toDateString())
            ->whereDoesntHave('videoCall', function($query) {
                $query->whereNotNull('started_at');
            })
            ->get();

        $count = 0;
        foreach ($expiredAppointments as $appointment) {
            $appointment->update([
                'status' => AppointmentStatus::CANCELLED->value
            ]);
            $count++;
        }

        $this->info("Updated {$count} expired appointments to cancelled status.");
    }
}
