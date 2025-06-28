<?php

namespace Modules\PatientManagement\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PatientManagement\Database\factories\ChronicConditionFactory;

class ChronicCondition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected static function newFactory(): ChronicConditionFactory
    {
        return ChronicConditionFactory::new();
    }

    public function patients()
    {
        return $this->belongsToMany(
            PatientProfile::class,
            'chronic_conditions_patient',
            'chronic_conditions_id',
            'patient_profile_id'
        )
            ->withTimestamps();
    }
}
