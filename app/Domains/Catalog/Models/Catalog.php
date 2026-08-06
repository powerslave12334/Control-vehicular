<?php

namespace App\Domains\Catalog\Models;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    protected $fillable = ['group', 'value', 'label'];

    public function scopeByGroup($query, string $group)
    {
        return $query->where('group', $group);
    }
}
