<?php

namespace Modules\AppointmentManagement\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Modules\AppointmentManagement\App\Enums\AppointmentStatus;
use Modules\AppointmentManagement\App\Http\Resources\AppointmentResource;
use Modules\AppointmentManagement\App\Models\Appointment;

class HomeVisitController extends Controller
{
    use ApiResponse;

    public function start(Appointment $appointment)
    {
        $appointment->update([
            'started_at' => now()
        ]);

        return $this->successResponse(
            new AppointmentResource($appointment),
            'Home Visit Started Successfully',
            200
        );
    }

    public function end(Appointment $appointment)
    {
        $appointment->update([
            'status' => AppointmentStatus::COMPLETED->value,
            'ended_at' => now()
        ]);

        return $this->successResponse(
            new AppointmentResource($appointment),
            'Home Visit Ended Successfully',
            200
        );
    }
}
