<?php

namespace Modules\DoctorManagement\App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\TimezoneService;
use Auth;
use Illuminate\Http\Request;
use Modules\AppointmentManagement\App\Models\Appointment;
use Modules\AppointmentManagement\App\Enums\AppointmentStatus;

class AppointmentController extends Controller
{
    protected $timezoneService;

    public function __construct(TimezoneService $timezoneService)
    {
        $this->timezoneService = $timezoneService;
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

        return back()->with('success', 'Appointment status updated successfully.');
    }

    public function reject(Appointment $appointment)
    {
        $appointment->update([
            'status' => AppointmentStatus::CANCELLED->value
        ]);

        return back()->with('success', 'Appointment status updated successfully.');
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
