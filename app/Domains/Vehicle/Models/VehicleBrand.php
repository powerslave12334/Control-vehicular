<?php

namespace App\Domains\Vehicle\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleBrand extends Model
{
    protected $table = 'vehicle_brands';

    protected $fillable = ['name'];

    public function models()
    {
        return $this->hasMany(VehicleModel::class, 'vehicle_brand_id');
    }
}
