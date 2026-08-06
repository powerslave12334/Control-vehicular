<?php

namespace App\Domains\Part\Models;

use Illuminate\Database\Eloquent\Model;

class PartCategory extends Model
{
    protected $table = 'part_categories';

    protected $fillable = ['name', 'description'];

    public function parts()
    {
        return $this->hasMany(Part::class, 'part_category_id');
    }
}
