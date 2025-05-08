<?php

namespace Modules\DoctorManagement\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\DoctorManagement\App\Http\Requests\AvailabilityRequest;
use Modules\DoctorManagement\App\Http\Resources\AvailabilityResource;
use Modules\DoctorManagement\App\Models\Availability;
use Modules\DoctorManagement\App\Services\AvailabilityService;

class AvailabilityController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly AvailabilityService $availabilityService
    ) {}

    public function index(): JsonResponse
    {
        $availabilities = $this->availabilityService->getDoctorAvailabilities(Auth::id());

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
