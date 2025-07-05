<?php

namespace Modules\DoctorManagement\App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\DoctorManagement\App\Http\Requests\web\AvailabilityRequest;
use Modules\DoctorManagement\App\Models\Availability;
use Modules\DoctorManagement\App\Services\AvailabilityService;

class AvailabilityController extends Controller
{

    public function __construct(
        private AvailabilityService $availabilityService
    ) {}

    public function index()
    {
        $availabilities = Auth::user()->doctorProfile->availabilities;

        return view('doctorDashboard.availabilities.index', compact('availabilities'));
    }

    public function show($id)
    {
        return response()->json($this->availabilityService->getAvailability($id));
    }

    public function update(AvailabilityRequest $request, $id)
    {
        $availability = Availability::findOrFail($id);

        $data = $request->validated();
        return response()->json($this->availabilityService->updateAvailability($availability, $data));
    }

    public function store(AvailabilityRequest $request)
    {
        $data = $request->validated();
        return response()->json($this->availabilityService->createAvailability($data));
    }

    public function destroy($id)
    {
        $availability = Availability::findOrFail($id);
        $this->availabilityService->deleteAvailability($availability);

        return response()->json(['success' => true]);
    }
}
