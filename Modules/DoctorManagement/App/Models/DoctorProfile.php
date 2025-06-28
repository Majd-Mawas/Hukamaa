<?php

namespace Modules\DoctorManagement\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\UserManagement\App\Models\User;
use Modules\SpecializationManagement\App\Models\Specialization;
use Modules\AppointmentManagement\App\Models\Appointment;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class DoctorProfile extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'birth_date',
        'gender',
        'phone_number',
        'address',
        'profile_picture',
        'specialization_id',
        'consultation_fee',
        'commission_percent',
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
        'birth_date' => 'date',
        'consultation_fee' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_picture')->singleFile();
        $this->addMediaCollection('identity_document')->singleFile();
        $this->addMediaCollection('practice_license')->singleFile();
        $this->addMediaCollection('medical_certificates');
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

    public function coverageAreas()
    {
        return $this->belongsToMany(CoverageArea::class, 'coverage_area_doctor');
    }
}
