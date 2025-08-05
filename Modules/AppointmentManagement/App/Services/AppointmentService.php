<?php

namespace Modules\AppointmentManagement\App\Services;

use App\Notifications\SystemNotification;
use App\Services\NotificationTemplateBuilder;
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
use Modules\UserManagement\App\Models\User;

class AppointmentService
{
    public function __construct(
        private readonly DoctorAvailabilityService $doctorAvailabilityService,
        public NotificationTemplateBuilder $notification_template_builder
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
                'service' => $data['service'],
            ]);

            $appointment->refresh();

            // if (isset($data['schedule'])) {
            //     $this->scheduleAppointment($appointment, $data['schedule'], $data['doctor_id']);
            // }

            $user = $doctorProfile->user;

            $template = $this->notification_template_builder->newPatientCase($user);
            sendDataMessage($user->fcm_token, $template);

            if (env('APP_NOTIFICATION')) {
                $user->notify(new SystemNotification(
                    $template['title'],
                    $template['message'],
                    $template['data']
                ));
            }

            sendDataMessage($user->fcm_token, $template);

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
        DB::beginTransaction();
        try {
            $appointment->update([
                'condition_description' => $data['condition_description'],
                'service' => $data['service'],
                'home_visit_address' => $data['home_visit_address'],
                'home_visit_phone' => $data['home_visit_phone'],
            ]);

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

    public function deleteAppointment(Appointment $appointment): bool
    {
        return $appointment->delete();
    }

    public function createAppointmentDateTime(array $timeSlots): Appointment
    {
        $appointmentTimes = [];
        $doctorProfile = DoctorProfile::where('id', $timeSlots['doctor_id'])->firstOrFail();

        if ($timeSlots) {
            $appointmentTimes['start_time'] = Carbon::parse($timeSlots['start_time']);
            $appointmentTimes['end_time'] = $timeSlots['end_time'] ?? Carbon::parse($timeSlots['start_time'])->addMinutes(30);
            $appointmentTimes['date'] = Carbon::parse($timeSlots['date']);

            // Create new appointment
            $appointment = Appointment::create([
                'patient_id' => Auth::id(),
                'doctor_id' => $doctorProfile->user_id,
                'date' => $appointmentTimes['date'],
                'start_time' => $appointmentTimes['start_time'],
                'end_time' => $appointmentTimes['end_time'],
                'status' => AppointmentStatus::PENDING,
                'confirmed_by_doctor' => false,
                'confirmed_by_patient' => true
            ]);
            logger()->info($appointment);
            return $appointment->fresh(['patient', 'doctor']);
        }

        throw ValidationException::withMessages([
            'schedule' => ['Time slots are required.']
        ]);
    }

    public function confirmPayment(Appointment $appointment, array $data): Appointment
    {
        $doctorProfile = $appointment->doctor->doctorProfile;
        $paymentReference = 'PAY-' . strtoupper(Str::random(8));

        $fee = $doctorProfile->consultation_fee;
        $commissionRate = $doctorProfile->commission_percent / 100;

        $adminCommission = round($fee * $commissionRate, 2);
        $doctorEarning = round($fee - $adminCommission, 2);

        Payment::create([
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $appointment->doctor_id,
            'appointment_id' => $appointment->id,
            'amount' => $fee,
            'admin_commission' => $adminCommission,
            'doctor_earning' => $doctorEarning,
            'payment_reference' => $paymentReference,
            'status' => 'pending',
        ]);

        // Add the invoice file to the payment_invoices collection
        if (isset($data['invoice_file'])) {
            $appointment->addMedia($data['invoice_file'])
                ->toMediaCollection('payment_invoices');
        }
        $template = $this->notification_template_builder->paymentNeedsApproval($appointment);

        if (env('APP_NOTIFICATION')) {
            getAdminUser()->notify(new SystemNotification(
                $template['title'],
                $template['message'],
                $template['data']
            ));
        }

        return $appointment->fresh();
    }

    public function getUpcomingAppointments(int $userId)
    {
        return Appointment::where('patient_id', $userId)
            ->whereNotIn('status', [AppointmentStatus::COMPLETED, AppointmentStatus::CANCELLED, AppointmentStatus::PENDING])
            ->with(['doctor'])
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
                // AppointmentStatus::PENDING_PAYMENT->value
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

        $user = User::findOrFail($appointment->patient_id);

        $template = $this->notification_template_builder->appointmentDecision($appointment, $data['action']);

        if (env('APP_NOTIFICATION')) {
            $user->notify(new SystemNotification($template['title'], $template['message'], $template['data']));
        }

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
            ->with('appointmentReport')
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
