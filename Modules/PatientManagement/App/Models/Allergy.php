<?php

namespace Modules\PatientManagement\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PatientManagement\Database\factories\AllergyFactory;

class Allergy extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected static function newFactory(): AllergyFactory
    {
        return AllergyFactory::new();
    }

    public function patients()
    {
        return $this->belongsToMany(
            PatientProfile::class,
            'allergy_patient',
            'allergy_id',
            'patient_profile_id'
        )
            ->withTimestamps();
    }
}
