<?php

namespace Modules\DoctorManagement\App\Services;

use Modules\AppointmentManagement\App\Models\Appointment;
use Modules\AppointmentManagement\App\Enums\AppointmentStatus;
use Modules\PaymentManagement\App\Models\Payment;
use Carbon\Carbon;

class DoctorStatisticsService
{
    public function getDoctorStatistics(int $doctorId): array
    {
        return [
            'total_patients' => $this->getPatientsStatistics($doctorId),
            'total_appointments' => $this->getAppointmentsStatistics($doctorId),
            'appointments_this_month' => $this->getAcceptedAppointmentsStatistics($doctorId),
            'financial_dues' => $this->getFinancialStatistics($doctorId),
        ];
    }
    private function getPatientsStatistics(int $doctorId): array
    {
        $currentPatients = Appointment::where('doctor_id', $doctorId)
            ->pluck('patient_id')
            ->unique()
            ->count();
        $previousPatients = Appointment::where('doctor_id', $doctorId)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->pluck('patient_id')
            ->unique()
            ->count();

        return [
            'count' => $currentPatients,
            'percentage_change' => $this->calculatePercentageChange($previousPatients, $currentPatients),
        ];
    }

    private function getAppointmentsStatistics(int $doctorId): array
    {
        $currentCount = Appointment::where('doctor_id', $doctorId)
            // ->whereMonth('date', now()->month)
            ->count();
        $previousCount = Appointment::where('doctor_id', $doctorId)
            // ->whereMonth('date', now()->subMonth()->month)
            ->count();

        return [
            'count' => $currentCount,
            'percentage_change' => $this->calculatePercentageChange($previousCount, $currentCount),
        ];
    }

    private function getAcceptedAppointmentsStatistics(int $doctorId): array
    {
        $currentCount = Appointment::where('doctor_id', $doctorId)
            ->whereMonth('date', now()->month)
            ->whereNotIn('status', [
                AppointmentStatus::CANCELLED->value,
                AppointmentStatus::PENDING->value,
                AppointmentStatus::PENDING_PAYMENT->value
            ])
            ->count();

        $previousCount = Appointment::where('doctor_id', $doctorId)
            ->whereMonth('date', now()->subMonth()->month)
            ->whereNotIn('status', [
                AppointmentStatus::CANCELLED->value,
                AppointmentStatus::PENDING->value,
                AppointmentStatus::PENDING_PAYMENT->value
            ])
            ->count();

        return [
            'count' => $currentCount,
            'percentage_change' => $this->calculatePercentageChange($previousCount, $currentCount),
        ];
    }

    private function getFinancialStatistics(int $doctorId): array
    {
        $currentMonth = now()->month;
        $dues = Payment::where('doctor_id', $doctorId)
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->whereMonth('created_at', '>=', $currentMonth - 5)
            ->whereMonth('created_at', '<=', $currentMonth)
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        $months = array_map(function($month) use ($currentMonth) {
            $monthNumber = $currentMonth - ($month - 1);
            if ($monthNumber <= 0) {
                $monthNumber += 12;
            }
            return trans('appointmentmanagement::appointments.months.' . strtolower(Carbon::create()->month($monthNumber)->format('F')));
        }, range(1, 6));

        $monthlyDues = array_fill(0, 6, 0);

        foreach ($dues as $due) {
            $month = (int)$due->month;
            $index = array_search($month, range($currentMonth, $currentMonth - 5, -1));
            if ($index !== false) {
                $monthlyDues[$index] = (float)$due->total;
            }
        }

        return [
            'labels' => array_reverse($months),
            'data' => array_values($monthlyDues),
        ];
    }

    private function calculatePercentageChange(int $previous, int $current): float
    {
        if ($previous === 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 2);
    }
}
