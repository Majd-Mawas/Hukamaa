<?php

namespace Modules\AppointmentManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\UserManagement\Models\User;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'date',
        'time_slot',
        'status',
        'confirmed_by_doctor',
        'confirmed_by_patient',
    ];

    protected $casts = [
        'date' => 'date',
        'confirmed_by_doctor' => 'boolean',
        'confirmed_by_patient' => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function videoCall()
    {
        return $this->hasOne(VideoCall::class);
    }
}
