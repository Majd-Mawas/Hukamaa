<?php

namespace Modules\DoctorManagement\App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\PatientManagement\App\Models\Allergy;
use Modules\PatientManagement\App\Models\ChronicCondition;
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

    public function edit(PatientProfile $patient)
    {
        $patient->load('allergies', 'chronicConditions');
        $allergies = Allergy::all();
        $chronicConditions = ChronicCondition::all();

        return view('doctorDashboard.patients.edit', compact('patient', 'allergies', 'chronicConditions'));
    }

    public function show(PatientProfile $patient)
    {
        $patient->load(['user', 'media']);
        $files = $patient->getMedia('patient_files');

        return view('doctorDashboard.patients.show', compact('patient', 'files'));
    }

    public function update(Request $request, PatientProfile $patient)
    {
        $patient->update([
            'current_medications' => $request->current_medications,
            'medical_history' => $request->medical_history,
        ]);

        $patient->allergies()->sync($request->allergies);
        $patient->chronicConditions()->sync($request->chronicConditions);

        return redirect()->route('doctor.patients.index')->with('success', 'Patient Updated Successfully.');
    }
}
