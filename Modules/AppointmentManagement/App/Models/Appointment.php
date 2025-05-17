<?php

namespace Modules\AppointmentManagement\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\UserManagement\App\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Modules\AppointmentManagement\App\Enums\AppointmentStatus;

class Appointment extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'confirmed_by_doctor',
        'confirmed_by_patient',
        'condition_description',
    ];

    protected $casts = [
        'date' => 'date',
        'confirmed_by_doctor' => 'boolean',
        'confirmed_by_patient' => 'boolean',
        'status' => AppointmentStatus::class,
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('appointment_files')
            ->acceptsMimeTypes(['application/pdf', 'image/jpeg', 'image/png']);

        $this->addMediaCollection('payment_invoices')
            ->acceptsMimeTypes(['application/pdf', 'image/jpeg', 'image/png'])
            ->singleFile();
    }

    public function getPaymentInvoiceAttribute()
    {
        return $this->getFirstMedia('payment_invoices');
    }
}
