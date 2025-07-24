<?php

namespace Modules\AdminPanel\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Notifications\SystemNotification;
use App\Services\NotificationTemplateBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\AppointmentManagement\App\Enums\AppointmentStatus;
use Modules\PaymentManagement\App\Models\Payment;
use Modules\PaymentManagement\App\Enums\PaymentStatus;

class PaymentController extends Controller
{
    public function __construct(public NotificationTemplateBuilder $notification_template_builder) {}

    public function index()
    {
        $payments = Payment::with(['patient:id,name', 'doctor:id,name'])
            ->whereNot('status', PaymentStatus::PENDING)
            ->latest()
            ->paginate(10);

        return view('adminDashboard.payments.index', compact('payments'));
    }

    public function approve(Payment $payment)
    {
        $payment->update([
            'status' => PaymentStatus::APPROVED,
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);

        $payment->appointment->update([
            'status' => AppointmentStatus::SCHEDULED
        ]);

        $appointment = $payment->appointment;
        $user = $appointment->patient;

        $template = $this->notification_template_builder->confirmedAppointment($user);

        if (env('APP_NOTIFICATION')) {
            $user->notify(new SystemNotification(
                $template['title'],
                $template['message'],
                $template['data']
            ));
        }

        sendDataMessage($user->fcm_token, $template);

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

        return view('adminDashboard.payments.index', compact('payments', 'status'));
    }
}
