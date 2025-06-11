<?php

namespace Modules\AppointmentManagement\App\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\AppointmentManagement\App\Enums\AppointmentStatus;
use Modules\AppointmentManagement\App\Models\Appointment;
use Modules\DoctorManagement\App\Models\DoctorProfile;
use Modules\DoctorManagement\App\Services\DoctorAvailabilityService;
use Modules\PaymentManagement\App\Models\Payment;

class AppointmentService
{
    public function __construct(
        private readonly DoctorAvailabilityService $doctorAvailabilityService
    ) {}
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
        DB::beginTransaction();
        try {
            $doctorProfile = DoctorProfile::where('id', $data['doctor_id'])->firstOrFail();
            $appointment = Appointment::create([
                'patient_id' => Auth::id(),
                'doctor_id' => $doctorProfile->user_id,
                'date' => $data['date'] ?? today(),
                'condition_description' => $data['condition_description'],
                'status' => AppointmentStatus::PENDING,
                'confirmed_by_doctor' => false,
                'confirmed_by_patient' => true,
            ]);

            // if (isset($data['schedule'])) {
            //     $this->scheduleAppointment($appointment, $data['schedule'], $data['doctor_id']);
            // }

            if (isset($data['files'])) {
                foreach ($data['files'] as $file) {
                    $appointment->addMedia($file)
                        ->toMediaCollection('appointment_files');
                }
            }

            DB::commit();
            return $appointment->fresh(['patient', 'doctor']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
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
    public function getDoneAppointments(int $userId)
    {
        return Appointment::where('patient_id', $userId)
            ->where('status', AppointmentStatus::COMPLETED)
            ->get();
    }

    public function getDoctorPendingAppointments(int $doctorId)
    {
        return Appointment::where('doctor_id', $doctorId)
            ->whereIn('status', [
                AppointmentStatus::PENDING->value,
                AppointmentStatus::PENDING_PAYMENT->value
            ])
            ->with(['patient'])
            ->latest()
            ->paginate(10);
    }
    public function decideAppointment(Appointment $appointment, array $data): Appointment
    {
        $appointment->update([
            'status' => $data['action'] === 'accept' ? AppointmentStatus::PENDING_PAYMENT : AppointmentStatus::CANCELLED,
            'confirmed_by_doctor' => $data['action'] === 'accept' ? true : false,
        ]);
        return $appointment->fresh();
    }

    public function getDoctorUpcomingAppointments(int $userId)
    {
        return Appointment::where('doctor_id', $userId)
            ->where('status', AppointmentStatus::SCHEDULED)
            ->get();
    }
    public function getDoctorDoneAppointments(int $userId)
    {
        return Appointment::where('doctor_id', $userId)
            ->where('status', AppointmentStatus::COMPLETED)
            ->get();
    }

    private function scheduleAppointment(Appointment $appointment, array $schedule, $doctorId): void
    {
        if (!$this->doctorAvailabilityService->isSlotAvailable(
            $doctorId,
            $schedule['date'],
            $schedule['start_time'],
            $schedule['end_time']
        )) {
            throw ValidationException::withMessages([
                'schedule' => ['Selected time slot is not available.']
            ]);
        }

        $appointment->update([
            'date' => $schedule['date'],
            'start_time' => $schedule['start_time'],
            'end_time' => $schedule['end_time'],
        ]);
    }
}
