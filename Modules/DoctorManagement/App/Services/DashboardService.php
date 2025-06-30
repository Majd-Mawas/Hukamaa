<?php

namespace Modules\DoctorManagement\App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Modules\AppointmentManagement\App\Enums\AppointmentStatus;
use Modules\AppointmentManagement\App\Models\Appointment;
use Modules\PatientManagement\App\Models\PatientProfile;
use Modules\PaymentManagement\App\Models\Payment;

class DashboardService
{
    private const CACHE_TTL = 1;

    public function getDoctorDashboardStats(): array
    {
        $doctorId = Auth::id();

        return Cache::remember('doctor_dashboard_stats_' . $doctorId, self::CACHE_TTL, function () use ($doctorId) {
            $totalPatients = PatientProfile::whereHas('appointments', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            })->count();

            $totalAppointments = Appointment::where('doctor_id', $doctorId)
                ->whereNot('status', AppointmentStatus::CANCELLED)
                ->count();

            $newPatientsThisWeek = PatientProfile::whereHas('appointments', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId)
                    ->where('created_at', '>=', now()->subWeek());
            })->count();

            $newAppointmentsThisWeek = Appointment::where('doctor_id', $doctorId)
                ->where('created_at', '>=', now()->subWeek())
                ->whereNot('status', AppointmentStatus::CANCELLED)
                ->count();

            $monthlyPayments = Payment::where('doctor_id', $doctorId)
                ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
                ->groupBy('month')
                ->pluck('total', 'month')
                ->toArray();

            $monthlyPaymentsFormatted = [];
            foreach (range(1, 12) as $month) {
                $monthlyPaymentsFormatted[] = round($monthlyPayments[$month] ?? 0, 2);
            }

            $upcomingAppointments = Appointment::where('doctor_id', $doctorId)
                ->where('date', '>=', now())
                ->whereNotIn('status', [AppointmentStatus::CANCELLED, AppointmentStatus::COMPLETED])
                ->with(['patient.patientProfile'])
                ->orderBy('date')
                ->limit(4)
                ->get();

            $totalEarnings = Payment::where('doctor_id', $doctorId)->sum('amount');
            $monthlyEarnings = Payment::where('doctor_id', $doctorId)
                ->whereMonth('created_at', now()->month)
                ->sum('amount');
            return [
                'patients' => [
                    'total' => $totalPatients,
                    'new_this_week' => $newPatientsThisWeek
                ],
                'appointments' => [
                    'total' => $totalAppointments,
                    'new_this_week' => $newAppointmentsThisWeek,
                    'upcoming' => $upcomingAppointments
                ],
                'earnings' => [
                    'total' => $totalEarnings,
                    'monthly' => $monthlyEarnings,
                    'monthly_chart' => $monthlyPaymentsFormatted
                ]
            ];
        });
    }
}
