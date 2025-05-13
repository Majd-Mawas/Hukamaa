<?php

namespace Modules\DoctorManagement\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\UserManagement\App\Models\User;
use Modules\SpecializationManagement\App\Models\Specialization;
use Modules\AppointmentManagement\App\Models\Appointment;

class DoctorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'birth_date',
        'gender',
        'phone_number',
        'address',
        'profile_picture',
        'specialization_id',
        'title',
        'experience_years',
        'experience_description',
        'certificates',
        'status',
        'services',
        'coverage_area',
        'expertise_focus'
    ];

    protected $casts = [
        'certificates' => 'array',
        'experience_years' => 'integer',
        'services' => 'array',
        'birth_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class, 'doctor_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }
}
