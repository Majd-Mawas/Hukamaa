<?php

namespace Modules\AppointmentManagement\App\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\AppointmentManagement\App\Enums\AppointmentStatus;
use Modules\AppointmentManagement\App\Models\Appointment;
use Modules\DoctorManagement\App\Models\DoctorProfile;
use Modules\PaymentManagement\App\Models\Payment;

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
        $doctorProfile = DoctorProfile::where('id', $data['doctor_id'])->firstOrFail();
        $appointment = Appointment::create([
            'patient_id' => Auth::id(),
            'doctor_id' => $doctorProfile->user_id,
            'date' => $data['date'] ?? now(),
            'condition_description' => $data['condition_description'],
            'status' => AppointmentStatus::PENDING,
            'confirmed_by_doctor' => false,
            'confirmed_by_patient' => true,
        ]);

        if (isset($data['files'])) {
            foreach ($data['files'] as $file) {
                $appointment->addMedia($file)
                    ->toMediaCollection('appointment_files');
            }
        }

        return $appointment->load(['patient', 'doctor']);
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

    public function confirmAppointmentDateTime(Appointment $appointment, ?array $timeSlots = null): Appointment
    {
        $updates = [];

        // $user = Auth::user();
        // if ($user->role === 'doctor') {
        //     $updates['confirmed_by_doctor'] = true;
        // } else {
        //     $updates['confirmed_by_patient'] = true;
        // }

        if ($timeSlots) {
            $updates['start_time'] = Carbon::parse($timeSlots['start_time']);
            $updates['end_time'] = $timeSlots['end_time'] ?? Carbon::parse($timeSlots['start_time'])->addHours(1);
            $updates['date'] = Carbon::parse($timeSlots['date']);
        }

        $appointment->update($updates);
        return $appointment->fresh();
    }

    public function confirmPayment(Appointment $appointment, array $data): Appointment
    {
        // Get the doctor's profile to get the consultation fee
        $doctorProfile = $appointment->doctor->doctorProfile ?? throw new ModelNotFoundException('Doctor profile not found');

        // Generate a unique payment reference
        $paymentReference = 'PAY-' . strtoupper(Str::random(8));

        // Create payment record
        Payment::create([
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $appointment->doctor_id,
            'appointment_id' => $appointment->id,
            'amount' => $doctorProfile->consultation_fee,
            'payment_reference' => $paymentReference,
            'status' => 'pending'
        ]);

        // Add the invoice file to the payment_invoices collection
        if (isset($data['invoice_file'])) {
            $appointment->addMedia($data['invoice_file'])
                ->toMediaCollection('payment_invoices');
        }

        return $appointment->fresh();
    }

    public function getUpcomingAppointments(int $userId)
    {
        return Appointment::where('patient_id', $userId)
            ->whereNot('status', AppointmentStatus::COMPLETED)
            ->get();
    }
}
