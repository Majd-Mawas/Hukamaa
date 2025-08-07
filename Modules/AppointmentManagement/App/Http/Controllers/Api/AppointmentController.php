<?php

namespace Modules\AppointmentManagement\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Notifications\SystemNotification;
use App\Services\NotificationTemplateBuilder;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\AppointmentManagement\App\Enums\AppointmentStatus;
use Modules\AppointmentManagement\App\Http\Requests\AppointmentRequest;
use Modules\AppointmentManagement\App\Http\Requests\ConfirmAppointmentRequest;
use Modules\AppointmentManagement\App\Http\Requests\ConfirmPaymentRequest;
use Modules\AppointmentManagement\App\Http\Requests\CreateAppointmentRequest;
use Modules\AppointmentManagement\App\Http\Requests\GetAvailableSlotsRequest;
use Modules\AppointmentManagement\App\Http\Requests\AppointmentReportRequest;
use Modules\AppointmentManagement\App\Models\AppointmentReport;
use Modules\AppointmentManagement\App\Http\Resources\AppointmentResource;
use Modules\AppointmentManagement\App\Models\Appointment;
use Modules\AppointmentManagement\App\Services\AppointmentService;
use Modules\AppointmentManagement\App\Http\Requests\DoctorDecideAppointmentRequest;
use Modules\AppointmentManagement\App\Http\Requests\UpdateAppointmentRequest;
use Modules\DoctorManagement\App\Services\AvailabilityService;
use Modules\DoctorManagement\App\Services\DoctorAvailabilityService;
use Illuminate\Validation\ValidationException;

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
            __('appointmentmanagement::appointments.messages.appointments_retrieved'),
        );
    }

    public function store(CreateAppointmentRequest $request): JsonResponse
    {
        $appointment = $this->appointmentService->createAppointment($request->validated());

        return $this->successResponse(
            new AppointmentResource($appointment->load(['patient', 'doctor'])),
            __('appointmentmanagement::appointments.messages.appointment_created'),
            201
        );
    }

    public function show(Appointment $appointment): JsonResponse
    {
        return $this->successResponse(
            new AppointmentResource($appointment->load(['patient', 'doctor', 'videoCall'])),
            __('appointmentmanagement::appointments.messages.appointment_retrieved')
        );
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment): JsonResponse
    {
        $updatedAppointment = $this->appointmentService->updateAppointment($appointment, $request->validated());

        return $this->successResponse(
            new AppointmentResource($updatedAppointment),
            __('appointmentmanagement::appointments.messages.appointment_updated')
        );
    }

    public function destroy(Appointment $appointment): JsonResponse
    {
        $this->appointmentService->deleteAppointment($appointment);

        return $this->successResponse(
            null,
            __('appointmentmanagement::appointments.messages.appointment_cancelled')
        );
    }

    public function confirmDateTime(ConfirmAppointmentRequest $request): JsonResponse
    {
        try {
            $timeSlots = $request->only(['start_time', 'end_time', 'date', 'doctor_id']);

            if (!$this->doctorAvailabilityService->isSlotAvailable(
                $timeSlots['doctor_id'],
                $timeSlots['date'],
                $timeSlots['start_time'],
                $timeSlots['end_time']
            )) {
                throw ValidationException::withMessages([
                    'schedule' => ['Selected time slot is not available.']
                ]);
            }
            $appointment = $this->appointmentService->createAppointmentDateTime($timeSlots);

            if (!$appointment) {
                return $this->errorResponse(
                    __('appointmentmanagement::appointments.messages.appointment_not_found'),
                    404
                );
            }

            return $this->successResponse(
                new AppointmentResource($appointment),
                __('appointmentmanagement::appointments.messages.appointment_confirmed')
            );
        } catch (\Exception $e) {
            logger()->info($e);
            return $this->errorResponse(
                $e->getMessage(),
                500
            );
        }
    }

    public function confirmPayment(ConfirmPaymentRequest $request, Appointment $appointment): JsonResponse
    {
        $data = $request->validated();

        $updatedAppointment = $this->appointmentService->confirmPayment($appointment, $data);

        return $this->successResponse(
            new AppointmentResource($updatedAppointment),
            __('appointmentmanagement::appointments.messages.payment_confirmed')

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
            __('appointmentmanagement::appointments.messages.available_slots')

        );
    }

    public function getUpcomingAppointments(): JsonResponse
    {
        $appointments = $this->appointmentService->getUpcomingAppointments(Auth::id());

        return $this->successResponse(
            AppointmentResource::collection($appointments->load('doctor')),
            __('appointmentmanagement::appointments.messages.upcoming_appointments')
        );
    }
    public function getDoneAppointments(): JsonResponse
    {
        $appointments = $this->appointmentService->getDoneAppointments(Auth::id());

        return $this->successResponse(
            AppointmentResource::collection($appointments->load(['doctor', 'appointmentReport'])),
            __('appointmentmanagement::appointments.messages.appointments_retrieved')

        );
    }

    public function pendingAppointments(): JsonResponse
    {
        $doctorId = Auth::id();
        $appointments = $this->appointmentService->getDoctorPendingAppointments($doctorId);

        return $this->successResponse(
            AppointmentResource::collection($appointments),
            __('appointmentmanagement::appointments.messages.appointments_retrieved')
        );
    }

    public function submitReport(AppointmentReportRequest $request, Appointment $appointment): JsonResponse
    {
        try {
            $report = AppointmentReport::updateOrCreate(
                ['appointment_id' => $appointment->id],
                $request->validated()
            );

            $appointment->update([
                'status' => AppointmentStatus::COMPLETED->value
            ]);

            $patient = $appointment->patient;
            $template = app(NotificationTemplateBuilder::class)->appointmentReportAdded($appointment);

            $patient->notify(new SystemNotification(
                $template['title'],
                $template['message'],
                $template['data']
            ));

            if ($patient->fcm_token) {
                sendDataMessage($patient->fcm_token, $template);
            }

            return $this->successResponse(
                new AppointmentResource($appointment->load('appointmentReport')),
                'Report Submitted Successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function updateReport(AppointmentReportRequest $request, Appointment $appointment): JsonResponse
    {
        try {
            $report = $appointment->appointmentReport;

            if (!$report) {
                return $this->errorResponse(
                    'No report found for this appointment. Please submit a report first.',
                    404
                );
            }

            $report->update($request->validated());

            $patient = $appointment->patient;
            $template = app(NotificationTemplateBuilder::class)->appointmentReportUpdated($appointment);

            $patient->notify(new SystemNotification(
                $template['title'],
                $template['message'],
                $template['data']
            ));

            if ($patient->fcm_token) {
                sendDataMessage($patient->fcm_token, $template);
            }

            return $this->successResponse(
                new AppointmentResource($appointment->load('appointmentReport')),
                'Report Updated Successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function decideAppointment(DoctorDecideAppointmentRequest $request, Appointment $appointment)
    {
        $appointment = $this->appointmentService->decideAppointment($appointment, $request->validated());

        return $this->successResponse(
            new AppointmentResource($appointment),
            __('appointmentmanagement::appointments.messages.appointment_decided')
        );
    }

    public function getDoctorUpcomingAppointments(): JsonResponse
    {
        $appointments = $this->appointmentService->getDoctorUpcomingAppointments(Auth::id());

        return $this->successResponse(
            AppointmentResource::collection($appointments?->load('patient')),
            __('appointmentmanagement::appointments.messages.upcoming_appointments')
        );
    }
    public function getDoctorDoneAppointments(): JsonResponse
    {
        $appointments = $this->appointmentService->getDoctorDoneAppointments(Auth::id());

        return $this->successResponse(
            AppointmentResource::collection($appointments->load('patient', 'videoCall')),
            __('appointmentmanagement::appointments.messages.upcoming_appointments')
        );
    }
}
