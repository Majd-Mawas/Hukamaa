<?php

namespace Modules\AppointmentManagement\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentReport extends Model
{
    protected $table = 'appointments_reports';

    protected $fillable = [
        'appointment_id',
        'diagnosis',
        'prescription',
        'additional_notes',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }
}
