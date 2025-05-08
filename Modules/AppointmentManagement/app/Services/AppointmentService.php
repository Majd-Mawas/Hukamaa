<?php

namespace Modules\AppointmentManagement\App\Services;

use Illuminate\Support\Facades\Auth;
use Modules\AppointmentManagement\App\Models\Appointment;

class AppointmentService
{
    public function getUserAppointments(int $userId)
    {
        return Appointment::where('patient_id', $userId)
            ->orWhere('doctor_id', $userId)
            ->with(['patient', 'doctor'])
            ->latest()
            ->paginate(10);
    }

    public function createAppointment(array $data): Appointment
    {
        return Appointment::create($data);
    }

    public function updateAppointment(Appointment $appointment, array $data): Appointment
    {
        $appointment->update($data);
        return $appointment->fresh();
    }

    public function deleteAppointment(Appointment $appointment): bool
    {
        return $appointment->delete();
    }

    public function confirmAppointment(Appointment $appointment): Appointment
    {
        $user = Auth::user();

        if ($user->role === 'doctor') {
            $appointment->update(['confirmed_by_doctor' => true]);
        } else {
            $appointment->update(['confirmed_by_patient' => true]);
        }

        return $appointment->fresh();
    }
}
