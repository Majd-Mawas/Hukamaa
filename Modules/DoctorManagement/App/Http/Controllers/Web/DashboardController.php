<?php

namespace Modules\DoctorManagement\App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAvailabilityRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\DoctorManagement\App\Http\Requests\web\AvailabilityRequest;
use Modules\DoctorManagement\App\Http\Requests\web\UpdateDoctorProfileRequest;
use Modules\DoctorManagement\App\Services\DashboardService;
use Modules\SpecializationManagement\App\Models\Specialization;
use Modules\DoctorManagement\App\Services\DoctorProfileService;
use Modules\DoctorManagement\App\Services\AvailabilityService;

class DashboardController extends Controller
{
    protected $doctorProfileService;
    protected $availabilityService;

    public function __construct(
        private DashboardService $dashboardService,
        DoctorProfileService $doctorProfileService,
        AvailabilityService $availabilityService
    ) {

        $this->doctorProfileService = $doctorProfileService;
        $this->availabilityService = $availabilityService;
    }

    public function index()
    {
        $stats = (object) $this->dashboardService->getDoctorDashboardStats();

        return view('doctorDashboard.index', compact('stats'));
    }

    public function viewProfile()
    {
        $doctor = Auth::User()->doctorProfile()->with('specialization', 'appointments')->withCount(['appointments as patients_count' => function ($query) {
            $query->select(DB::raw('COUNT(DISTINCT patient_id)'));
        }])->first();

        $specialties = Specialization::orderBy('specialization_name')->get();
        $availabilities = Auth::user()->doctorProfile->availabilities;

        return view('doctorDashboard.viewProfile', compact('doctor', 'specialties', 'availabilities'));
    }

    public function updateProfile(UpdateDoctorProfileRequest $request)
    {
        try {
            $this->doctorProfileService->updateProfile($request);

            return redirect()
                ->back()
                ->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            return $e->getMessage();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update profile. ' . $e->getMessage());
        }
    }

    public function getAvailability($id)
    {
        return response()->json($this->availabilityService->getAvailability($id));
    }

    public function updateAvailability(AvailabilityRequest $request, $id)
    {
        $data = $request->validated();
        return response()->json($this->availabilityService->updateAvailability($id, $data));
    }

    public function storeAvailability(AvailabilityRequest $request)
    {
        $data = $request->validated();
        return response()->json($this->availabilityService->createAvailability($data));
    }
}
