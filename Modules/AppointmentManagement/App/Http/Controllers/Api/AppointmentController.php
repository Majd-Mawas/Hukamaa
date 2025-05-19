<?php

namespace Modules\AppointmentManagement\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\AppointmentManagement\App\Http\Requests\AppointmentRequest;
use Modules\AppointmentManagement\App\Http\Requests\ConfirmAppointmentRequest;
use Modules\AppointmentManagement\App\Http\Requests\ConfirmPaymentRequest;
use Modules\AppointmentManagement\App\Http\Requests\CreateAppointmentRequest;
use Modules\AppointmentManagement\App\Http\Requests\GetAvailableSlotsRequest;
use Modules\AppointmentManagement\App\Http\Resources\AppointmentResource;
use Modules\AppointmentManagement\App\Models\Appointment;
use Modules\AppointmentManagement\App\Services\AppointmentService;
use Modules\DoctorManagement\App\Services\AvailabilityService;
use Modules\DoctorManagement\App\Services\DoctorAvailabilityService;

class AppointmentController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly AppointmentService $appointmentService,
        private readonly AvailabilityService $availabilityService,
        private readonly DoctorAvailabilityService $doctorAvailabilityService
    ) {}

    public function index(): JsonResponse
    {
        $appointments = $this->appointmentService->getUserAppointments(Auth::id());

        return $this->successResponse(
            AppointmentResource::collection($appointments),
            'Appointments retrieved successfully'
        );
    }

    public function store(CreateAppointmentRequest $request): JsonResponse
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

    public function confirmDateTime(ConfirmAppointmentRequest $request, Appointment $appointment): JsonResponse
    {
        $user = Auth::user();
        $timeSlots = null;

        // if ($user->role === 'doctor') {
        // }
        $timeSlots = $request->only(['start_time', 'end_time', 'date']);

        $updatedAppointment = $this->appointmentService->confirmAppointmentDateTime($appointment, $timeSlots);

        return $this->successResponse(
            new AppointmentResource($updatedAppointment),
            'Appointment date and time confirmed successfully'
        );
    }

    public function confirmPayment(ConfirmPaymentRequest $request, Appointment $appointment): JsonResponse
    {
        $data = $request->validated();

        $updatedAppointment = $this->appointmentService->confirmPayment($appointment, $data);

        return $this->successResponse(
            new AppointmentResource($updatedAppointment),
            'Payment confirmed successfully'
        );
    }

    public function getAvailableSlots(GetAvailableSlotsRequest $request): JsonResponse
    {
        $data = $request->validated();

        $slots = $this->doctorAvailabilityService->getAvailableSlots(
            $data['doctor_id'],
            $data['date'],
        );

        return $this->successResponse(
            $slots,
            'Available slots retrieved successfully'
        );
    }

    public function getUpcomingAppointments(): JsonResponse
    {
        $appointments = $this->appointmentService->getUpcomingAppointments(Auth::id());

        return $this->successResponse(
            AppointmentResource::collection($appointments->load('doctor')),
            'Upcoming appointments retrieved successfully'
        );
    }
}
