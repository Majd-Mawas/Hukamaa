<?php

namespace Modules\AdminPanel\App\Services;

use Illuminate\Support\Facades\Cache;
use Modules\AppointmentManagement\App\Enums\AppointmentStatus;
use Modules\AppointmentManagement\App\Models\Appointment;
use Modules\DoctorManagement\App\Models\DoctorProfile;
use Modules\PatientManagement\App\Models\PatientProfile;
use Modules\PaymentManagement\App\Models\Payment;
use Modules\UserManagement\App\Models\User;

class DashboardService
{
    private const CACHE_TTL = 1;

    public function getDashboardStats(): array
    {
        return Cache::remember('dashboard_stats', self::CACHE_TTL, function () {
            $totalDoctors = DoctorProfile::where('status', 'approved')->count();
            $totalPatients = PatientProfile::count();
            $totalAppointment = Appointment::whereNot('status', AppointmentStatus::CANCELLED)->count();

            $newDoctorsThisWeek = DoctorProfile::where('status', 'approved')
                ->where('created_at', '>=', now()->subWeek())
                ->count();

            $newPatientThisWeek = PatientProfile::where('created_at', '>=', now()->subWeek())
                ->count();

            $newAppointmentThisWeek = Appointment::where('created_at', '>=', now()->subWeek())
                ->whereNot('status', AppointmentStatus::CANCELLED)
                ->count();

            $totalStaff = User::where('role', 'staff')->count();
            $staffOnVacation = User::where('role', 'staff')
                ->where('status', 'on_vacation')
                ->count();

            $doctorGenderDistribution = DoctorProfile::selectRaw('gender, count(*) as count')
                ->whereNotNull('gender')
                ->groupBy('gender')
                ->pluck('count', 'gender')
                ->toArray();

            $patientGenderDistribution = PatientProfile::selectRaw('gender, count(*) as count')
                ->whereNotNull('gender')
                ->groupBy('gender')
                ->pluck('count', 'gender')
                ->toArray();

            $genderDistribution = [
                'doctors' => $doctorGenderDistribution,
                'patients' => $patientGenderDistribution
            ];

            $topSpecialtiesRaw = DoctorProfile::selectRaw('specialization_id, count(*) as count')
                ->where('status', 'approved')
                ->groupBy('specialization_id')
                ->with('specialization')
                ->orderByDesc('count')
                ->limit(3)
                ->get()
                ->map(function ($item) use ($totalDoctors) {
                    return [
                        'name' => $item->specialization->specialization_name,
                        'count' => $item->count,
                        'percentage' => $totalDoctors > 0 ? round(($item->count / $totalDoctors) * 100, 2) : 0
                    ];
                });

            $topSpecialtyNames = $topSpecialtiesRaw->pluck('name')->values()->all();
            $topSpecialtyCounts = $topSpecialtiesRaw->pluck('count')->values()->all();
            $topSpecialtyPercentages = $topSpecialtiesRaw->pluck('percentage')->values()->all();

            $monthlyPayments = Payment::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
                ->groupBy('month')
                ->pluck('total', 'month')
                ->toArray();

            $monthlyPaymentsFormatted = [];
            foreach (range(1, 12) as $month) {
                $monthlyPaymentsFormatted[] = round($monthlyPayments[$month] ?? 0, 2);
            }

            $doctors = DoctorProfile::with('user', 'specialization')->paginate(6);
            $appointment = Appointment::paginate(7);

            return [
                'doctors' => [
                    'total' => $totalDoctors,
                    'new_this_week' => $newDoctorsThisWeek
                ],
                'patients' => [
                    'total' => $totalPatients,
                    'new_this_week' => $newPatientThisWeek
                ],
                'appointments' => [
                    'total' => $totalAppointment,
                    'new_this_week' => $newAppointmentThisWeek
                ],
                'staff' => [
                    'total' => $totalStaff,
                    'on_vacation' => $staffOnVacation
                ],
                'payments' => [
                    'monthly' => $monthlyPaymentsFormatted,
                ],
                'top_specialties' => [
                    'names' => $topSpecialtyNames,
                    'counts' => $topSpecialtyCounts,
                    'percentages' => $topSpecialtyPercentages,
                ],
                'monthlyPayments' => $monthlyPaymentsFormatted,
                'gender_distribution' => $genderDistribution,
                'doctors_list' => $doctors,
                'appointment' => $appointment,
            ];
        });
    }
}
