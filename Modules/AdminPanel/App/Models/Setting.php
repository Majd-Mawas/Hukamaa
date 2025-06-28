<?php

namespace Modules\AdminPanel\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\AdminPanel\Database\Factories\SettingFactory;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    // protected static function newFactory(): SettingFactory
    // {
    //     // return SettingFactory::new();
    // }
}
