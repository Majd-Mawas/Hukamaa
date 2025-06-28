<?php

namespace Modules\AdminPanel\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\AdminPanel\App\Models\Setting;
use Modules\AdminPanel\App\Services\DashboardService;
use Modules\DoctorManagement\App\Http\Requests\web\UpdateDoctorProfileRequest;

class DashboardController extends Controller
{
    private $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $stats = (object) $this->dashboardService->getDashboardStats();
        // return $stats;
        return view('adminDashboard.index', compact('stats'));
    }

    public function viewProfile()
    {
        $doctor = Auth::User()->doctorProfile()->with('specialization', 'appointments')->withCount(['appointments as patients_count' => function ($query) {
            $query->select(DB::raw('COUNT(DISTINCT patient_id)'));
        }])->first();

        // $specialties = Specialization::orderBy('specialization_name')->get();
        // $availabilities = Auth::user()->doctorProfile->availabilities;

        return view('adminDashboard.profile.viewProfile', compact('doctor'));
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();

            $user->update([
                'email' => $request->email,
                'name' => $request->name
            ]);

            if (isset($request->password)) {
                $user->update([
                    'password' => $request->password,
                ]);
            }
            updateSetting('Account_Number', $request->account_number);

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
}
