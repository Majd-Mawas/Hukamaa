<?php

namespace Modules\AdminPanel\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\AppointmentManagement\App\Models\Appointment;
use Modules\AppointmentManagement\App\Enums\AppointmentStatus;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['patient:id,name', 'doctor:id,name'])
            ->latest()
            ->paginate(10);

        return view('adminDashboard.appointments.index', compact('appointments'));
    }

    public function pending()
    {
        $appointments = Appointment::where('status', AppointmentStatus::PENDING)
            ->with(['patient:id,name', 'doctor:id,name'])
            ->latest()
            ->paginate(10);
        $status = AppointmentStatus::PENDING->value;

        return view('adminDashboard.appointments.index', compact('appointments', 'status'));
    }

    public function completed()
    {
        $appointments = Appointment::where('status', AppointmentStatus::COMPLETED)
            ->with(['patient:id,name', 'doctor:id,name'])
            ->latest()
            ->paginate(10);
        $status = AppointmentStatus::COMPLETED->value;

        return view('adminDashboard.appointments.index', compact('appointments', 'status'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor', 'appointmentReport']);
        
        return view('adminDashboard.appointments.show', compact('appointment'));
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
}
