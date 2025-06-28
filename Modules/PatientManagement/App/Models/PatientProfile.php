<?php

namespace Modules\PatientManagement\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\UserManagement\App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\AppointmentManagement\App\Models\Appointment;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PatientProfile extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'gender',
        'birth_date',
        'medical_history',
        'current_medications',
        'is_profile_complete'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_profile_complete' => 'boolean',
        'medical_history' => 'array',
        'current_medications' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('patient_files')
            ->useDisk('media')
            ->acceptsMimeTypes(['application/pdf', 'image/jpeg', 'image/png'])
            ->withResponsiveImages();
    }

    public function preConsultationForms()
    {
        return $this->hasMany(PreConsultationForm::class, 'patient_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    public function allergies()
    {
        return $this->belongsToMany(
            Allergy::class,
            'allergy_patient',
            'patient_profile_id',
            'allergy_id'
        )
            ->withTimestamps();
    }

    public function chronicConditions()
    {
        return $this->belongsToMany(
            ChronicCondition::class,
            'chronic_conditions_patient',
            'patient_profile_id',
            'chronic_conditions_id'
        )
            ->withTimestamps();
    }
}
