<?php

namespace Modules\AppointmentManagement\App\Enums;

enum AppointmentStatus: string
{
    case SCHEDULED = 'scheduled';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case PENDING = 'pending';
    case PENDING_PAYMENT = 'pending-payment';
    public function label(): string
    {
        return match ($this) {
            self::SCHEDULED => 'Scheduled',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
            self::PENDING => 'Pending',
            self::PENDING_PAYMENT => 'Pending Payment',
        };
    }
}
