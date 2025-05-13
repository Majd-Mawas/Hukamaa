<?php

namespace Modules\DoctorManagement\App\Enums;

enum DoctorTitle: string
{
    case DR = 'Dr.';
    case PROF = 'Prof.';
    case ASSOC_PROF = 'Assoc. Prof.';
    case ASST_PROF = 'Asst. Prof.';

    public function label(): string
    {
        return match ($this) {
            self::DR => 'Doctor',
            self::PROF => 'Professor',
            self::ASSOC_PROF => 'Associate Professor',
            self::ASST_PROF => 'Assistant Professor',
        };
    }
}
