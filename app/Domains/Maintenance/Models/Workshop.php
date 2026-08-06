<?php

namespace App\Domains\Maintenance\Models;

use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    protected $table = 'workshops';

    protected $fillable = ['name', 'phone', 'address'];
}
