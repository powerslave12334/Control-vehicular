<?php

namespace App\Domains\Fuel\Models;

use Illuminate\Database\Eloquent\Model;

class FuelCard extends Model
{
    protected $table = 'fuel_cards';

    protected $fillable = ['card_number', 'holder_name', 'type', 'monthly_limit', 'status'];
}
