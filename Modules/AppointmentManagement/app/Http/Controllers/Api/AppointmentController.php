<?php

namespace Modules\AppointmentManagement\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Modules\AppointmentManagement\Http\Requests\AppointmentRequest;
use Modules\AppointmentManagement\Http\Resources\AppointmentResource;
use Modules\AppointmentManagement\Models\Appointment;
use Modules\AppointmentManagement\Services\AppointmentService;

class AppointmentController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly AppointmentService $appointmentService
    ) {}

    public function index(): JsonResponse
    {
        $appointments = $this->appointmentService->getUserAppointments(auth()->id());

        return $this->successResponse(
            AppointmentResource::collection($appointments),
            'Appointments retrieved successfully'
        );
    }

    public function store(AppointmentRequest $request): JsonResponse
    {
        $appointment = $this->appointmentService->createAppointment($request->validated());

        return $this->successResponse(
            new AppointmentResource($appointment),
            'Appointment created successfully',
            201
        );
    }

    public function show(Appointment $appointment): JsonResponse
    {
        return $this->successResponse(
            new AppointmentResource($appointment->load(['patient', 'doctor', 'videoCall'])),
            'Appointment retrieved successfully'
        );
    }

    public function update(AppointmentRequest $request, Appointment $appointment): JsonResponse
    {
        $updatedAppointment = $this->appointmentService->updateAppointment($appointment, $request->validated());

        return $this->successResponse(
            new AppointmentResource($updatedAppointment),
            'Appointment updated successfully'
        );
    }

    public function destroy(Appointment $appointment): JsonResponse
    {
        $this->appointmentService->deleteAppointment($appointment);

        return $this->successResponse(
            null,
            'Appointment cancelled successfully'
        );
    }

    public function confirm(Appointment $appointment): JsonResponse
    {
        $this->appointmentService->confirmAppointment($appointment);

        return $this->successResponse(
            new AppointmentResource($appointment->fresh()),
            'Appointment confirmed successfully'
        );
    }
}
