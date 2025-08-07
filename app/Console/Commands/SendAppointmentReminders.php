<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Notifications\AppointmentReminderPush;
use Modules\AppointmentManagement\App\Models\Appointment;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';
    protected $description = 'Send 24h and 20min reminders for upcoming appointments';

    public function handle()
    {
        $now = Carbon::now();

        $target24h = $now->copy()->addDay()->format('Y-m-d H:i');
        $appointments24h = Appointment::whereNotNull('start_time')
            ->get()
            ->filter(function ($appointment) use ($target24h) {
                $scheduled = Carbon::parse($appointment->date . ' ' . $appointment->start_time)->format('Y-m-d H:i');
                return $scheduled === $target24h;
            });

        foreach ($appointments24h as $appointment) {
            $appointment->doctor?->notify(new AppointmentReminderPush($appointment, '24h'));
            $appointment->patient?->notify(new AppointmentReminderPush($appointment, '24h'));
        }

        $target20m = $now->copy()->addMinutes(20)->format('Y-m-d H:i');
        $appointments20m = Appointment::whereNotNull('start_time')
            ->get()
            ->filter(function ($appointment) use ($target20m) {
                $scheduled = Carbon::parse($appointment->date . ' ' . $appointment->start_time)->format('Y-m-d H:i');
                return $scheduled === $target20m;
            });

        foreach ($appointments20m as $appointment) {
            $appointment->doctor?->notify(new AppointmentReminderPush($appointment));
            $appointment->patient?->notify(new AppointmentReminderPush($appointment));
        }

        $this->info('Reminders checked and sent.');
    }
}
