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
}
