<?php

namespace Modules\DoctorManagement\App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Notifications\SystemNotification;
use App\Services\NotificationTemplateBuilder;
use App\Services\TimezoneService;
use Auth;
use Illuminate\Http\Request;
use Modules\AppointmentManagement\App\Models\Appointment;
use Modules\AppointmentManagement\App\Enums\AppointmentStatus;
use Modules\DoctorManagement\App\Services\DoctorAvailabilityService;
use Modules\UserManagement\App\Models\User;

class AppointmentController extends Controller
{
    protected $timezoneService;
    protected $doctorAvailabilityService;

    public function __construct(
        TimezoneService $timezoneService,
        DoctorAvailabilityService $doctorAvailabilityService,
        public NotificationTemplateBuilder $notification_template_builder
    ) {
        $this->timezoneService = $timezoneService;
        $this->doctorAvailabilityService = $doctorAvailabilityService;
    }

    public function index()
    {
        $appointments = Appointment::where('doctor_id', Auth::id())
            ->with(['patient:id,name,timezone', 'doctor:id,name,timezone'])
            ->latest()
            ->paginate(10);

        // Add timezone-aware time ranges to each appointment
        $appointments->getCollection()->transform(function ($appointment) {
            $appointment->time_range = $this->getTimeRange($appointment);
            return $appointment;
        });

        return view('doctorDashboard.appointments.index', compact('appointments'));
    }

    public function new()
    {
        $appointments = Appointment::where('doctor_id', Auth::id())
            ->where('status', AppointmentStatus::PENDING)
            ->with(['patient:id,name,timezone', 'doctor:id,name,timezone'])
            ->latest()
            ->paginate(10);

        $appointments->getCollection()->transform(function ($appointment) {
            $appointment->time_range = $this->getTimeRange($appointment);
            return $appointment;
        });

        $status = AppointmentStatus::PENDING->value;

        return view('doctorDashboard.appointments.index', compact('appointments', 'status'));
    }

    public function upcoming()
    {
        $appointments = Appointment::where('doctor_id', Auth::id())
            ->where('status', AppointmentStatus::SCHEDULED)
            ->with(['patient:id,name,timezone', 'doctor:id,name,timezone'])
            ->latest()
            ->paginate(10);

        // Add timezone-aware time ranges to each appointment
        $appointments->getCollection()->transform(function ($appointment) {
            $appointment->time_range = $this->getTimeRange($appointment);
            return $appointment;
        });

        $status = AppointmentStatus::SCHEDULED->value;

        return view('doctorDashboard.appointments.index', compact('appointments', 'status'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor', 'appointmentReport']);
        $appointment->time_range = $this->getTimeRange($appointment);

        return view('doctorDashboard.appointments.show', compact('appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:' . implode(',', array_column(AppointmentStatus::cases(), 'value')),
        ]);

        $appointment->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Appointment status updated successfully.');
    }

    public function accept(Appointment $appointment)
    {
        $appointment->update([
            'status' => AppointmentStatus::PENDING_PAYMENT->value
        ]);

        $template = $this->notification_template_builder->appointmentDecision($appointment, 'accept');
        $user = User::findOrFail($appointment->patient_id);

        sendDataMessage($user->fcm_token, $template);
        $user->notify(new SystemNotification($template['title'], $template['message'], $template['data']));

        return back()->with('success', 'Appointment status updated successfully.');
    }

    public function reject(Appointment $appointment)
    {
        $appointment->update([
            'status' => AppointmentStatus::CANCELLED->value
        ]);

        $template = $this->notification_template_builder->appointmentDecision($appointment, 'reject');
        $user = User::findOrFail($appointment->patient_id);

        sendDataMessage($user->fcm_token, $template);
        $user->notify(new SystemNotification($template['title'], $template['message'], $template['data']));

        return back()->with('success', 'Appointment status updated successfully.');
    }

    public function updateTime(Request $request, Appointment $appointment)
    {
        try {
            $validated = $request->validate([
                'start_time' => 'required|date_format:H:i',
            ]);

            $doctorTimezone = $this->timezoneService->getUserTimezone();
            $patientTimezone = $appointment->patient->timezone ?? 'UTC';

            $startTime = $this->timezoneService->convertTimeBetweenTimezones(
                $validated['start_time'] . ':00',
                $doctorTimezone,
                $patientTimezone,
                $appointment->date->format('Y-m-d')
            );

            $startCarbon = \Carbon\Carbon::parse($startTime);
            $endTime = $startCarbon->copy()->addMinutes(30)->format('H:i:s');

            $appointment->update([
                'start_time' => $startTime,
                'end_time' => $endTime,
            ]);

            return back()->with('success', 'Appointment time updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating appointment time', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'An error occurred while updating the appointment time.']);
        }
    }

    private function getTimeRange(Appointment $appointment)
    {
        $doctorTimezone = $this->timezoneService->getUserTimezone();

        if ($appointment->start_time && $appointment->end_time) {
            $timeRange = $appointment->getTimeRangeInTimezone($doctorTimezone, $appointment->patient->timezone);

            return [
                'start_time' => $timeRange['start_time'] ?? null,
                'end_time' => $timeRange['end_time'] ?? null,
            ];
        }

        return null;
    }
}
