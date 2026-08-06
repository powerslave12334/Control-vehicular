<?php

namespace App\Domains\Part\Models;

use Illuminate\Database\Eloquent\Model;

class PartBrand extends Model
{
    protected $table = 'part_brands';

    protected $fillable = ['name'];

    public function parts()
    {
        return $this->hasMany(Part::class, 'part_brand_id');
    }
}
