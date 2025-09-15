<?php

namespace Modules\Area\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Traits\HasActivityLog;

// use Modules\Area\Database\Factories\CityFactory;

class City extends Model
{
    use HasActivityLog;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): CityFactory
    // {
    //     // return CityFactory::new();
    // }
}
