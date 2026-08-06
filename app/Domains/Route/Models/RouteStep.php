<?php

namespace App\Domains\Route\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RouteStep extends Model
{
    use SoftDeletes;
    protected $table = 'route_steps';
 
    protected $fillable = [
        'route_id', 'step_type', 'odometer', 'fuel_level', 'timestamp',
        'photo', 'liters', 'amount', 'ticket_photo', 'observations',
        'latitude', 'longitude',
    ];
 
    protected function casts(): array
    {
        return [
            'timestamp' => 'datetime',
            'liters' => 'decimal:2',
            'amount' => 'decimal:2',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }
 
    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
