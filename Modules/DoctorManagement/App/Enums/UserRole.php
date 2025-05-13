<?php

namespace Modules\DoctorManagement\App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case DOCTOR = 'doctor';
    case PATIENT = 'patient';
    case STAFF = 'staff';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::DOCTOR => 'Doctor',
            self::PATIENT => 'Patient',
            self::STAFF => 'Staff',
        };
    }

    public function permissions(): array
    {
        return match ($this) {
            self::ADMIN => ['*'],
            self::DOCTOR => [
                'view_own_profile',
                'edit_own_profile',
                'manage_own_availability',
                'view_own_appointments',
                'manage_own_appointments',
            ],
            self::PATIENT => [
                'view_own_profile',
                'edit_own_profile',
                'book_appointments',
                'view_own_appointments',
                'cancel_own_appointments',
            ],
            self::STAFF => [
                'view_profiles',
                'manage_appointments',
                'manage_availability',
            ],
        };
    }
}
