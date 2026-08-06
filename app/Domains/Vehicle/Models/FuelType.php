<?php

namespace App\Domains\Vehicle\Models;

use Illuminate\Database\Eloquent\Model;

class FuelType extends Model
{
    protected $table = 'fuel_types';

    protected $fillable = ['name'];
}
