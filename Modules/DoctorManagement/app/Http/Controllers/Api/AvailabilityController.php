<?php

namespace Modules\DoctorManagement\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Modules\DoctorManagement\Http\Requests\AvailabilityRequest;
use Modules\DoctorManagement\Http\Resources\AvailabilityResource;
use Modules\DoctorManagement\Models\Availability;
use Modules\DoctorManagement\Services\AvailabilityService;

class AvailabilityController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly AvailabilityService $availabilityService
    ) {}

    public function index(): JsonResponse
    {
        $availabilities = $this->availabilityService->getDoctorAvailabilities(auth()->id());

        return $this->successResponse(
            AvailabilityResource::collection($availabilities),
            'Availabilities retrieved successfully'
        );
    }

    public function store(AvailabilityRequest $request): JsonResponse
    {
        $availability = $this->availabilityService->createAvailability($request->validated());

        return $this->successResponse(
            new AvailabilityResource($availability),
            'Availability created successfully',
            201
        );
    }

    public function update(AvailabilityRequest $request, Availability $availability): JsonResponse
    {
        $updatedAvailability = $this->availabilityService->updateAvailability($availability, $request->validated());

        return $this->successResponse(
            new AvailabilityResource($updatedAvailability),
            'Availability updated successfully'
        );
    }

    public function destroy(Availability $availability): JsonResponse
    {
        $this->availabilityService->deleteAvailability($availability);

        return $this->successResponse(
            null,
            'Availability deleted successfully'
        );
    }
}
