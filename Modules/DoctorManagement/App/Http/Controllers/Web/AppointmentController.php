<?php

namespace Modules\DoctorManagement\App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Modules\AppointmentManagement\App\Models\Appointment;
use Modules\AppointmentManagement\App\Enums\AppointmentStatus;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::where('doctor_id', Auth::id())->with(['patient:id,name', 'doctor:id,name'])
            ->latest()
            ->paginate(10);

        return view('doctorDashboard.appointments.index', compact('appointments'));
    }

    public function new()
    {
        $appointments = Appointment::where('doctor_id', Auth::id())->where('status', AppointmentStatus::PENDING)
            ->with(['patient:id,name', 'doctor:id,name'])
            ->latest()
            ->paginate(10);
        $status = AppointmentStatus::PENDING->value;

        return view('doctorDashboard.appointments.index', compact('appointments', 'status'));
    }

    public function upcoming()
    {
        $appointments = Appointment::where('doctor_id', Auth::id())->where('status', AppointmentStatus::SCHEDULED)
            ->with(['patient:id,name', 'doctor:id,name'])
            ->latest()
            ->paginate(10);
        $status = AppointmentStatus::COMPLETED->value;

        return view('doctorDashboard.appointments.index', compact('appointments', 'status'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor', 'appointmentReport']);

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
}
