<?php

namespace Modules\AdminPanel\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\PaymentManagement\App\Models\Payment;
use Modules\PaymentManagement\App\Enums\PaymentStatus;

class PaymentController extends Controller
{
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
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

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
