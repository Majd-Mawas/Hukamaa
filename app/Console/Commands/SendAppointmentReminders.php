<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Notifications\AppointmentReminderPush;
use Modules\AppointmentManagement\App\Models\Appointment;
use Illuminate\Support\Facades\Log;
use App\Services\TimezoneService;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';
    protected $description = 'Send 24h and 20min reminders for upcoming appointments';

    protected $timezoneService;

    public function __construct(TimezoneService $timezoneService)
    {
        parent::__construct();
        $this->timezoneService = $timezoneService;
    }

    public function handle()
    {
        $now = Carbon::now()->setTimezone('UTC');

        $target24h = $now->copy()->addDay()->format('Y-m-d H:i');

        $appointments24h = Appointment::whereNotNull('start_time')
            ->get()
            ->filter(function ($appointment) use ($target24h) {
                $patientTimezone = $appointment->getPatientTimezone();
                $appointmentDateTime = $appointment->date->format('Y-m-d') . ' ' . $appointment->start_time;

                $scheduled = Carbon::createFromFormat('Y-m-d H:i:s', $appointmentDateTime, $patientTimezone)
                    ->setTimezone('UTC')
                    ->format('Y-m-d H:i');

                return $scheduled === $target24h;
            });

        foreach ($appointments24h as $appointment) {
            $appointment->doctor?->notify(new AppointmentReminderPush($appointment, '24h'));
            $appointment->patient?->notify(new AppointmentReminderPush($appointment, '24h'));
        }

        // 20 minute reminders
        $target20m = $now->copy()->addMinutes(20)->format('Y-m-d H:i');
        $appointments20m = Appointment::whereNotNull('start_time')
            ->get()
            ->filter(function ($appointment) use ($target20m) {
                // Convert appointment time from patient timezone to UTC
                $patientTimezone = $appointment->getPatientTimezone();
                $appointmentDateTime = $appointment->date->format('Y-m-d') . ' ' . $appointment->start_time;

                // Create Carbon instance in patient timezone and convert to UTC
                $scheduled = Carbon::createFromFormat('Y-m-d H:i:s', $appointmentDateTime, $patientTimezone)
                    ->setTimezone('UTC')
                    ->format('Y-m-d H:i');

                return $scheduled === $target20m;
            });

        Log::info('Found ' . $appointments20m->count() . ' appointments for 20min reminders');

        foreach ($appointments20m as $appointment) {
            Log::info('Sending 20min reminder for appointment ID: ' . $appointment->id, [
                'doctor_id' => $appointment->doctor?->id,
                'patient_id' => $appointment->patient?->id
            ]);
            $appointment->doctor?->notify(new AppointmentReminderPush($appointment));
            $appointment->patient?->notify(new AppointmentReminderPush($appointment));
        }

        Log::info('Reminder check completed');
        $this->info('Reminders checked and sent.');
    }
}
