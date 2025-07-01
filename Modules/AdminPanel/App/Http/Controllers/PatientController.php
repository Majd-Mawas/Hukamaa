<?php

namespace Modules\AdminPanel\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\PatientManagement\App\Models\PatientProfile;
use Modules\UserManagement\App\Models\User;

class PatientController extends Controller
{
    public function index()
    {
        $patients = PatientProfile::with(['user'])
            ->latest()
            ->paginate(10);

        return view('adminDashboard.patients.index', compact('patients'));
    }

    public function show(PatientProfile $patient)
    {
        // Load the patient with user and media relationships
        $patient->load(['user', 'media']);

        // Get all files from the patient_files collection
        $files = $patient->getMedia('patient_files');

        return view('adminDashboard.patients.show', compact('patient', 'files'));
    }

    public function destroy(PatientProfile $patient)
    {
        try {
            DB::beginTransaction();

            $user = $patient->user;

            $patient->clearMediaCollection('patient_files');

            $patient->delete();
            $user->delete();

            DB::commit();

            return redirect()->route('admin.patients.index')
                ->with('success', 'patient deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting patient: ' . $e->getMessage());
        }
    }
}
