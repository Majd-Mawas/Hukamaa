<?php

namespace Modules\DoctorManagement\App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Notifications\SystemNotification;
use App\Services\NotificationTemplateBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\PaymentManagement\App\Models\Payment;
use Modules\PaymentManagement\App\Enums\PaymentStatus;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['patient:id,name', 'doctor:id,name'])
            ->whereNot('status', PaymentStatus::PENDING)
            ->where('doctor_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('doctorDashboard.payments.index', compact('payments'));
    }

    public function approve(Payment $payment)
    {
        $payment->update([
            'status' => PaymentStatus::APPROVED,
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);

        $appointment = $payment->appointment;
        if ($appointment) {
            $patient = $appointment->patient;
            $patientTemplate = app(NotificationTemplateBuilder::class)->confirmedAppointment($appointment);

            $patient->notify(new SystemNotification(
                $patientTemplate['title'],
                $patientTemplate['message'],
                $patientTemplate['data']
            ));

            if ($patient->fcm_token) {
                sendDataMessage($patient->fcm_token, $patientTemplate);
            }

            $doctor = $appointment->doctor;
            $doctorTemplate = app(NotificationTemplateBuilder::class)->paymentApprovedForDoctor($appointment);

            $doctor->notify(new SystemNotification(
                $doctorTemplate['title'],
                $doctorTemplate['message'],
                $doctorTemplate['data']
            ));

            if ($doctor->fcm_token) {
                sendDataMessage($doctor->fcm_token, $doctorTemplate);
            }
        }

        return back()->with('success', 'Payment approved successfully.');
    }

    public function reject(Payment $payment)
    {
        $payment->update([
            'status' => PaymentStatus::REJECTED
        ]);

        return back()->with('success', 'Payment rejected successfully.');
    }

    public function pending()
    {
        $payments = Payment::where('status', PaymentStatus::PENDING)
            ->with(['patient:id,name', 'doctor:id,name'])
            ->latest()
            ->paginate(10);
        $status = PaymentStatus::PENDING->value;

        return view('doctorDashboard.payments.index', compact('payments', 'status'));
    }
}
