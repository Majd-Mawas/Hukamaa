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
}
