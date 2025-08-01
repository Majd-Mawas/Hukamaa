<?php

namespace Modules\AdminPanel\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Notifications\SystemNotification;
use App\Services\NotificationTemplateBuilder;
use Modules\DoctorManagement\App\Models\DoctorProfile;
use Modules\UserManagement\App\Models\User;
use Modules\SpecializationManagement\App\Models\Specialization;
use Modules\AdminPanel\App\Http\Requests\StoreDoctorRequest;
use Modules\AdminPanel\App\Http\Requests\UpdateDoctorRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Modules\DoctorManagement\App\Enums\DoctorStatus;

class DoctorController extends Controller
{

    public function __construct(public NotificationTemplateBuilder $notification_template_builder) {}

    public function index()
    {
        $doctors = DoctorProfile::with(['user', 'specialization', 'coverageAreas'])
            ->latest()
            ->paginate(10);

        return view('adminDashboard.doctors.index', compact('doctors'));
    }

    public function create()
    {
        $specializations = Specialization::all();
        return view('adminDashboard.doctors.create', compact('specializations'));
    }

    public function store(StoreDoctorRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'doctor'
            ]);

            $doctor = DoctorProfile::create([
                'user_id' => $user->id,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'specialization_id' => $request->specialization_id,
                'consultation_fee' => $request->consultation_fee,
                'title' => $request->title,
                'experience_years' => $request->experience_years,
                'experience_description' => $request->experience_description,
                'services' => $request->services,
                'coverage_area' => $request->coverage_area,
                'expertise_focus' => $request->expertise_focus,
                'status' => 'active'
            ]);

            $this->handleMediaUploads($request, $doctor);

            DB::commit();

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating doctor: ' . $e->getMessage());
        }
    }

    public function show(DoctorProfile $doctor)
    {
        $doctor->load([
            'user',
            'specialization',
            'coverageAreas',
            'availabilities',
            'appointments' => fn($query) => $query->latest()->take(5)
        ]);
        return view('adminDashboard.doctors.show', compact('doctor'));
    }

    public function edit(DoctorProfile $doctor)
    {
        $doctor->load(['user', 'specialization']);
        $specializations = Specialization::all();

        return view('adminDashboard.doctors.edit', compact('doctor', 'specializations'));
    }

    public function update(UpdateDoctorRequest $request, DoctorProfile $doctor)
    {
        try {
            DB::beginTransaction();

            $doctor->user->update([
                'name' => $request->name,
                'email' => $request->email
            ]);

            $doctor->update([
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'specialization_id' => $request->specialization_id,
                'consultation_fee' => $request->consultation_fee,
                'title' => $request->title,
                'experience_years' => $request->experience_years,
                'experience_description' => $request->experience_description,
                'services' => $request->services,
                'coverage_area' => $request->coverage_area,
                'expertise_focus' => $request->expertise_focus
            ]);

            $this->handleMediaUploads($request, $doctor);

            DB::commit();

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating doctor: ' . $e->getMessage());
        }
    }

    public function destroy(DoctorProfile $doctor)
    {
        try {
            DB::beginTransaction();

            $user = $doctor->user;

            // Delete media files
            $doctor->clearMediaCollection('profile_picture');
            $doctor->clearMediaCollection('medical_certificates');

            // Delete the doctor profile and associated user
            $doctor->delete();
            $user->delete();

            DB::commit();

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting doctor: ' . $e->getMessage());
        }
    }

    private function handleMediaUploads($request, $doctor)
    {
        if ($request->hasFile('profile_picture')) {
            $doctor->clearMediaCollection('profile_picture');
            $doctor->addMediaFromRequest('profile_picture')
                ->toMediaCollection('profile_picture');
        }

        if ($request->hasFile('certificates')) {
            foreach ($request->file('certificates') as $certificate) {
                $doctor->addMedia($certificate)
                    ->toMediaCollection('medical_certificates');
            }
        }
    }

    public function doctorApprovals()
    {
        $pendingDoctors = DoctorProfile::with('user')
            ->whereIn('status', [DoctorStatus::PENDING->value, DoctorStatus::REJECTED->value])
            ->get();

        return view('adminDashboard.doctors.doctorApprovals', compact('pendingDoctors'));
    }

    public function approveDoctor(Request $request, DoctorProfile $doctorProfile)
    {
        $doctorProfile->update([
            'status' => DoctorStatus::APPROVED->value,
            'consultation_fee' => request()->consultation_fee,
            'commission_percent' => request()->commission_percent,
        ]);

        $user = $doctorProfile->user;

        $template = $this->notification_template_builder->approvedDoctorAccount($user);

        if (env('APP_NOTIFICATION')) {
            $user->notify(new SystemNotification(
                $template['title'],
                $template['message'],
                $template['data']
            ));
        }
        sendDataMessage($user->fcm_token, $template);

        return redirect()->route('admin.doctors.doctorApprovals')->with('success', 'Doctor approved successfully.');
    }

    public function rejectDoctor(Request $request, DoctorProfile $doctorProfile)
    {
        $doctorProfile->update(['status' => DoctorStatus::REJECTED->value]);

        $user = $doctorProfile->user;

        $template = $this->notification_template_builder->rejectedDoctorAccount($user);

        if (env('APP_NOTIFICATION')) {
            $user->notify(new SystemNotification(
                $template['title'],
                $template['message'],
                $template['data']
            ));
        }

        sendDataMessage($user->fcm_token, $template);

        return redirect()->route('admin.doctors.doctorApprovals')->with('success', 'Doctor rejected successfully.');
    }
}
