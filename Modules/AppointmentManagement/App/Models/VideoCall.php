<?php

namespace Modules\AppointmentManagement\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoCall extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'started_at',
        'ended_at',
        'call_duration',
        'status',
        'room_id',
        'doctor_token',
        'patient_token',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'call_duration' => 'integer',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
