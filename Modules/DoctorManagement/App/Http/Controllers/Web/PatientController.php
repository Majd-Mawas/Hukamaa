<?php

namespace Modules\DoctorManagement\App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Modules\PatientManagement\App\Models\PatientProfile;
use Modules\UserManagement\App\Models\User;

class PatientController extends Controller
{
    public function index()
    {
        $patients = PatientProfile::whereHas('appointments', function ($query) {
            $query->where('doctor_id', Auth::id());
        })->paginate(10);

        return view('doctorDashboard.patients.index', compact('patients'));
    }

    public function show(PatientProfile $patient)
    {
        // Load the patient with user and media relationships
        $patient->load(['user', 'media']);

        // Get all files from the patient_files collection
        $files = $patient->getMedia('patient_files');

        return view('doctorDashboard.patients.show', compact('patient', 'files'));
    }
}
